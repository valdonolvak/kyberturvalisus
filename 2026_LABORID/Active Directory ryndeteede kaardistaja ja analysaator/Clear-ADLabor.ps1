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

# Eemalda istutatud grupp Domain Adminsist (muidu jääb pesastatud tee alles,
# kui grupp mingil põhjusel kustutamata jääb)
try {
    Remove-ADGroupMember -Identity 'Domain Admins' -Members 'Serveri-Admins' -Confirm:$false -ErrorAction Stop
    Write-Host "  Eemaldatud: Serveri-Admins Domain Adminsist" -ForegroundColor Green
} catch { Write-Host "  (Serveri-Admins polnud Domain Adminsis)" -ForegroundColor Gray }

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

Write-Host "`nValmis. NB: DCSync/WriteDacl ACE-d olid objektidel, mis kustutati koos OU-ga." -ForegroundColor Cyan
