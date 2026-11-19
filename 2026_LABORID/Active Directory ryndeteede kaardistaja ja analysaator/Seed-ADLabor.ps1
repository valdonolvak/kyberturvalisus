#Requires -Modules ActiveDirectory
#Requires -RunAsAdministrator
<#
.SYNOPSIS
    Külvab küberturbe laborisse tahtlike väärseadistustega AD-objektid,
    et õppijad saaksid BloodHoundiga ründeteid (attack paths) avastada.

.DESCRIPTION
    Loob OU "Labor" alla kasutajad, grupid, teenusekonto ja mitu
    pahatahtlikku ACL-i / grupiseost. Iga seadistus vastab ühele
    BloodHoundi servale (edge), mille õppija peab üles leidma ja
    kirjeldama, kuidas seda ründaja ära kasutaks ja kaitsja parandaks.

.NOTES
    !!! KASUTADA AINULT ISOLEERITUD LABORIDOMEENIS !!!
    Ära kunagi jooksuta tootmis- ega kooli päris-AD vastu.
    Skript on teadlikult "ebaturvaline" — see ongi harjutuse mõte.

    Jooksuta domeenikontrolleril või domeeniga liidetud haldusjaamas,
    kus on RSAT AD PowerShell moodul, Domain Admins õigustes.
#>

[CmdletBinding()]
param(
    # Teadlikult nõrgad laboriparoolid
    [string]$LabPassword     = 'Suvi2026!',
    [string]$ServicePassword = 'Password1',   # Kerberoastimiseks: cracki'b sekunditega
    [string]$OUName          = 'Labor'
)

$ErrorActionPreference = 'Stop'
Import-Module ActiveDirectory

Write-Host "=== AD laborikeskkonna külv ===" -ForegroundColor Cyan

$domain     = Get-ADDomain
$domainDN   = $domain.DistinguishedName
$domainDNS  = $domain.DNSRoot
$labOU      = "OU=$OUName,$domainDN"
$securePw   = ConvertTo-SecureString $LabPassword     -AsPlainText -Force
$svcSecurePw= ConvertTo-SecureString $ServicePassword -AsPlainText -Force

Write-Host "Domeen: $domainDNS ($domainDN)" -ForegroundColor Gray

# --- Hoiatus / kaitse tootmise eest ---------------------------------------
if ($domainDNS -notmatch 'labor|lab|test|kyber|training') {
    Write-Warning "Domeeni nimi ei sisalda 'labor/lab/test/kyber'. Oled KINDEL, et see on laborikeskkond?"
    $ans = Read-Host "Kirjuta 'JAH', et jätkata"
    if ($ans -ne 'JAH') { throw "Katkestatud kasutaja poolt." }
}

# --- OU --------------------------------------------------------------------
if (-not (Get-ADOrganizationalUnit -Filter "Name -eq '$OUName'" -ErrorAction SilentlyContinue)) {
    New-ADOrganizationalUnit -Name $OUName -Path $domainDN -ProtectedFromAccidentalDeletion $false
    Write-Host "Loodud OU: $labOU" -ForegroundColor Green
}

# --- Abifunktsioonid -------------------------------------------------------
function New-LabUser {
    param([string]$Sam, [string]$Name, [System.Security.SecureString]$Pw = $securePw)
    $u = Get-ADUser -Filter "SamAccountName -eq '$Sam'" -ErrorAction SilentlyContinue
    if (-not $u) {
        New-ADUser -SamAccountName $Sam -Name $Name -DisplayName $Name `
            -UserPrincipalName "$Sam@$domainDNS" -AccountPassword $Pw -Enabled $true `
            -Path $labOU -PasswordNeverExpires $true
        Write-Host "  + kasutaja: $Sam" -ForegroundColor Green
    }
    return Get-ADUser -Identity $Sam
}

function New-LabGroup {
    param([string]$Name, [string]$Scope = 'Global')
    $g = Get-ADGroup -Filter "Name -eq '$Name'" -ErrorAction SilentlyContinue
    if (-not $g) {
        New-ADGroup -Name $Name -GroupScope $Scope -GroupCategory Security -Path $labOU
        Write-Host "  + grupp: $Name" -ForegroundColor Green
    }
    return Get-ADGroup -Identity $Name
}

# Lisab objekti ACL-i ühe ACE. $Right nt 'GenericAll','WriteDacl','ExtendedRight'.
function Add-LabAce {
    param(
        [string]$TargetDN,
        [string]$PrincipalSid,
        [string]$Right,
        [string]$ObjectTypeGuid = '00000000-0000-0000-0000-000000000000'
    )
    $path = "AD:\$TargetDN"
    $acl  = Get-Acl -Path $path
    $sid  = New-Object System.Security.Principal.SecurityIdentifier($PrincipalSid)
    $adr  = [System.DirectoryServices.ActiveDirectoryRights]$Right
    $allow= [System.Security.AccessControl.AccessControlType]::Allow
    $none = [System.DirectoryServices.ActiveDirectorySecurityInheritance]::None

    if ($ObjectTypeGuid -eq '00000000-0000-0000-0000-000000000000') {
        $ace = New-Object System.DirectoryServices.ActiveDirectoryAccessRule($sid,$adr,$allow,$none)
    } else {
        $ace = New-Object System.DirectoryServices.ActiveDirectoryAccessRule($sid,$adr,$allow,([Guid]$ObjectTypeGuid),$none)
    }
    $acl.AddAccessRule($ace)
    Set-Acl -Path $path -AclObject $acl
}

# Laiendatud õiguste GUID-id
$GUID_DCSync_GetChanges    = '1131f6aa-9c07-11d1-f79f-00c04fc2dcd2'
$GUID_DCSync_GetChangesAll = '1131f6ad-9c07-11d1-f79f-00c04fc2dcd2'
$GUID_ForceChangePassword  = '00299570-246d-11d0-a768-00aa006e0529'

# ===========================================================================
#  KASUTAJAD
# ===========================================================================
Write-Host "`n-- Kasutajad --" -ForegroundColor Cyan
$jaan   = New-LabUser 'jaan.tamm'   'Jaan Tamm'      # õppija stardipunkt (madal priv)
$mari   = New-LabUser 'mari.kask'   'Mari Kask'
$peeter = New-LabUser 'peeter.saar' 'Peeter Saar'
$liis   = New-LabUser 'liis.oja'    'Liis Oja'
$svc    = New-LabUser 'svc-sql'     'SQL teenusekonto' $svcSecurePw

# ===========================================================================
#  GRUPID + PESASTATUD LIIKMELISUS
# ===========================================================================
Write-Host "`n-- Grupid --" -ForegroundColor Cyan
$gHelpdesk = New-LabGroup 'Helpdesk'
$gServeri  = New-LabGroup 'Serveri-Admins'
$gToojaam  = New-LabGroup 'Toojaama-Admins'

# jaan alustab Helpdeskis (tavaõigused)
Add-ADGroupMember -Identity $gHelpdesk -Members $jaan

# svc-sql on Serveri-Adminsi liige (Kerberoasti tee algab siit)
Add-ADGroupMember -Identity $gServeri -Members $svc
#
# MÄRKUS: Serveri-Admins EI ole Domain Adminsi liige.
# Varasem disain pani ta DA liikmeks, aga siis muutub grupp AdminSDHolder'i
# kaitse alla (adminCount=1) ja SDProp lähtestab iga ~60 min selle ACL-i,
# kustutades jaan'i GenericAll ACE -> rünne lakkab töötamast.
# Selle asemel saab grupp DCSync-õiguse domeenil (VÄÄRSEADISTUS 1 allpool) —
# DCSync on võrdväärne või tugevam täieliku ülevõtu tee ja domeeniobjekti
# ACL-i AdminSDHolder EI lähtesta, nii et tee püsib.

# ===========================================================================
#  PAHATAHTLIKUD ACL-id (BloodHoundi servad)
# ===========================================================================
Write-Host "`n-- ACL väärseadistused --" -ForegroundColor Cyan

# VÄÄRSEADISTUS 1 — DCSync grupile (asendab varasema DA-liikmelisuse):
#   Serveri-Admins saab domeenilt GetChanges + GetChangesAll. Kes grupis on
#   (nt lisatud jaan.tamm või Kerberoastitud svc-sql), saab replitseerida
#   paroolihashid (sh krbtgt) -> Golden Ticket -> täielik ülevõtt.
#   Domeeniobjekti ACL-i AdminSDHolder ei lähtesta -> tee on püsiv.
Add-LabAce -TargetDN $domainDN -PrincipalSid $gServeri.SID.Value -Right 'ExtendedRight' -ObjectTypeGuid $GUID_DCSync_GetChanges
Add-LabAce -TargetDN $domainDN -PrincipalSid $gServeri.SID.Value -Right 'ExtendedRight' -ObjectTypeGuid $GUID_DCSync_GetChangesAll
Write-Host "  [1] Serveri-Admins --DCSync (GetChanges/All)--> domeen" -ForegroundColor Yellow

# VÄÄRSEADISTUS 2 — GenericAll grupi üle:
#   jaan.tamm -> GenericAll -> Serveri-Admins (saab end gruppi lisada -> grupp DCSync -> ülevõtt)
Add-LabAce -TargetDN $gServeri.DistinguishedName -PrincipalSid $jaan.SID.Value -Right 'GenericAll'
Write-Host "  [2] jaan.tamm --GenericAll--> Serveri-Admins" -ForegroundColor Yellow

# VÄÄRSEADISTUS 3 — ForceChangePassword:
#   mari.kask saab lähtestada peeter.saar parooli
Add-LabAce -TargetDN $peeter.DistinguishedName -PrincipalSid $mari.SID.Value `
           -Right 'ExtendedRight' -ObjectTypeGuid $GUID_ForceChangePassword
Write-Host "  [3] mari.kask --ForceChangePassword--> peeter.saar" -ForegroundColor Yellow

# VÄÄRSEADISTUS 4 — DCSync:
#   liis.oja saab domeeni objektilt GetChanges + GetChangesAll (= paroolihashide replikaat)
Add-LabAce -TargetDN $domainDN -PrincipalSid $liis.SID.Value -Right 'ExtendedRight' -ObjectTypeGuid $GUID_DCSync_GetChanges
Add-LabAce -TargetDN $domainDN -PrincipalSid $liis.SID.Value -Right 'ExtendedRight' -ObjectTypeGuid $GUID_DCSync_GetChangesAll
Write-Host "  [4] liis.oja --DCSync (GetChanges/All)--> domeen" -ForegroundColor Yellow

# VÄÄRSEADISTUS 5 — WriteDacl:
#   peeter.saar saab muuta Helpdeski ACL-i (saab endale õigusi juurde kirjutada)
Add-LabAce -TargetDN $gHelpdesk.DistinguishedName -PrincipalSid $peeter.SID.Value -Right 'WriteDacl'
Write-Host "  [5] peeter.saar --WriteDacl--> Helpdesk" -ForegroundColor Yellow

# ===========================================================================
#  VÄÄRSEADISTUS 6 — Kerberoasting
# ===========================================================================
Write-Host "`n-- Kerberoasting --" -ForegroundColor Cyan
Set-ADUser -Identity 'svc-sql' -ServicePrincipalNames @{ Replace = "MSSQLSvc/sql01.$domainDNS:1433" }
Set-ADAccountPassword -Identity 'svc-sql' -Reset -NewPassword $svcSecurePw
Write-Host "  [6] svc-sql: SPN seatud (HasSPN) + nõrk paroolina '$ServicePassword' -> Kerberoastitav" -ForegroundColor Yellow

# ===========================================================================
#  KOKKUVÕTE
# ===========================================================================
Write-Host "`n=== Külv valmis. Istutatud ründeteed: ===" -ForegroundColor Cyan
@"
  [1] DCSync       : Serveri-Admins -> domeen (grupil GetChanges/All; PÜSIV, SDProp ei lähtesta)
  [2] GenericAll   : jaan.tamm -> Serveri-Admins -> (AddMember) -> grupil DCSync -> ülevõtt
  [3] ForceChangePw: mari.kask -> peeter.saar
  [4] DCSync       : liis.oja -> domeen (paroolihashide väljavõte)
  [5] WriteDacl    : peeter.saar -> Helpdesk
  [6] Kerberoast   : svc-sql (SPN + nõrk parool; Serveri-Adminsi liige -> DCSync tee peal)

  Õppija stardikonto: jaan.tamm / $LabPassword
  Teenusekonto      : svc-sql   / $ServicePassword
"@ | Write-Host -ForegroundColor Green

Write-Host "`nSoovitus: tee DC-st VM hetktõmmis, et saaks laborit cohortide vahel lähtestada." -ForegroundColor Gray
