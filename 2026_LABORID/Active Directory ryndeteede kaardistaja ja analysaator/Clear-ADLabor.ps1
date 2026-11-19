#Requires -Modules ActiveDirectory
#Requires -RunAsAdministrator
<#
.SYNOPSIS
    Eemaldab Seed-ADLabor.ps1 loodud laboriobjektid ja taastab
    Domain Adminsi grupi puhtaks.

.NOTES
    Kustutab kogu OU "Labor" sisu. Kasuta ainult laboridomeenis.
    Lihtsaim alternatiiv: taasta DC VM hetktõmmisest.
#>

[CmdletBinding()]
param([string]$OUName = 'Labor')

$ErrorActionPreference = 'Continue'
Import-Module ActiveDirectory

$domainDN = (Get-ADDomain).DistinguishedName
$labOU    = "OU=$OUName,$domainDN"

Write-Host "=== Labori puhastus ===" -ForegroundColor Cyan

# 1) Eemalda DCSync (jm) ACE-d DOMEENIOBJEKTILT — need EI asu kustutatavas OU-s!
#    Serveri-Admins ja liis.oja said GetChanges/All domeeni juurel; kui jätta
#    puhastamata, jääb domeenile orbudeks jäänud SID-idega DCSync-õigus (ise
#    juba leid). Kogu nende SID-id ENNE OU kustutamist ja eemalda vastavad ACE-d.
$domainAcl   = Get-Acl "AD:\$domainDN"
$labSids = @()
foreach ($n in @('Serveri-Admins','liis.oja')) {
    $o = Get-ADObject -Filter "Name -eq '$n'" -SearchBase "OU=$OUName,$domainDN" -Properties objectSid -ErrorAction SilentlyContinue
    if ($o -and $o.objectSid) { $labSids += $o.objectSid.Value }
}
$removed = 0
foreach ($ace in @($domainAcl.Access)) {
    if ($ace.IdentityReference -is [System.Security.Principal.SecurityIdentifier] -and
        $labSids -contains $ace.IdentityReference.Value) {
        [void]$domainAcl.RemoveAccessRule($ace); $removed++
    }
}
if ($removed -gt 0) {
    Set-Acl "AD:\$domainDN" $domainAcl
    Write-Host "  Eemaldatud $removed domeeni-ACE (DCSync) laboriprintsipaalidelt" -ForegroundColor Green
} else {
    Write-Host "  (domeenilt ei leitud labori DCSync-ACE-sid)" -ForegroundColor Gray
}

$ou = Get-ADOrganizationalUnit -Filter "Name -eq '$OUName'" -ErrorAction SilentlyContinue
if ($ou) {
    # Eemalda juhusliku kustutuse kaitse ja kustuta OU koos sisuga
    Set-ADOrganizationalUnit -Identity $ou -ProtectedFromAccidentalDeletion $false -ErrorAction SilentlyContinue
    Get-ADObject -SearchBase $labOU -Filter * -ErrorAction SilentlyContinue |
        Where-Object { $_.DistinguishedName -ne $labOU } |
        ForEach-Object { Set-ADObject -Identity $_ -ProtectedFromAccidentalDeletion $false -ErrorAction SilentlyContinue }
    Remove-ADOrganizationalUnit -Identity $labOU -Recursive -Confirm:$false
    Write-Host "  Kustutatud OU ja kogu sisu: $labOU" -ForegroundColor Green
} else {
    Write-Host "  OU '$OUName' ei leitud — midagi pole kustutada." -ForegroundColor Gray
}

Write-Host "`nValmis. Domeeni DCSync-ACE-d eemaldatud eraldi; GenericAll/WriteDacl/ForceChangePassword ACE-d kustusid koos OU objektidega." -ForegroundColor Cyan
Write-Host "Kõige puhtam lähtestus on siiski DC VM hetktõmmis." -ForegroundColor Gray
