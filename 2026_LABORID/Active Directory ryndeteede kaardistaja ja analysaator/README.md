# Labor: Active Directory ründeteede kaardistamine (BloodHound)

Praktiline küberturbe labor, kus õppija võtab ühe tavalise AD-kasutaja konto ja jõuab sellest **täieliku domeeni ülevõtuni**, kasutades **BloodHound Community Editionit** ründeteede (attack paths) leidmiseks. Laboris on tahtlikult sisse ehitatud kuus levinud AD väärseadistust (GenericAll, DCSync, ForceChangePassword, Kerberoast, WriteDacl), mille õppija peab leidma, ära kasutama ja seejärel kaitsja vaatest parandama.

> ⚠️ **Ainult isoleeritud laborikeskkonnas.** Needsamad tegevused võõra või tootmis-AD vastu on seadusevastased (KarS § 206/217).

---

## Mis fail milleks on

| Fail | Kellele | Mida sisaldab |
|---|---|---|
| **[Opilase-juhend.md](./Opilase-juhend.md)** | 👨‍🎓 õpilane | **Samm-sammuline tööjuhend** labori iseseisvaks tegemiseks — mida teha ja *miks*. Alusta siit. |
| **[BloodHound-labor.md](./BloodHound-labor.md)** | 👩‍🏫 õpetaja + ülevaade | Labori **ülddokument**: õpieesmärgid, topoloogia, õpetaja ettevalmistus (Osa A), kogumine/import (Osa B), ülesanded (Osa C), kaitse (Osa D), hindamine. |
| **[BloodHound-CE-kasutusjuhend.md](./BloodHound-CE-kasutusjuhend.md)** | 👨‍🎓 õpilane | BloodHound CE **veebiliidese kasutusjuhend**: sõlmed, servad, Search/Pathfinding/Cypher, Inbound/Outbound, Abuse Info. |
| **[Rundeteede-lahenduskaik.md](./Rundeteede-lahenduskaik.md)** | 👩‍🏫 õpetaja / lahendus | Iga tee **lahenduskäik** (ärakasutamine Kali+Windows käskudega) ja **kasutajate/gruppide legend**. Õpilane vaatab alles pärast iseseisvat proovimist. |
| **[Seed-ADLabor.ps1](./Seed-ADLabor.ps1)** | 👩‍🏫 õpetaja | PowerShell **külvskript** — loob laboridomeeni kontod, grupid ja kuus väärseadistust. Päises on labori legend. |
| **[Clear-ADLabor.ps1](./Clear-ADLabor.ps1)** | 👩‍🏫 õpetaja | **Puhastusskript** labori lähtestamiseks cohortide vahel. |
| `pildid/` | — | Ekraanipildid (nt veebiliides). |

---

## Kuidas alustada

### 👩‍🏫 Õpetaja
1. Püstita isoleeritud laboridomeen (vt [BloodHound-labor.md](./BloodHound-labor.md), Osa A).
2. Jooksuta DC-l `Seed-ADLabor.ps1` (Domain Admins õigustes) — külvab väärseadistused.
3. Tee DC-st VM-hetktõmmis (kiireks lähtestuseks).
4. Anna õppijale **ainult** stardikonto `jaan.tamm` / `Suvi2026!`.
5. Lähtestamiseks: `Clear-ADLabor.ps1` või taasta hetktõmmis.

### 👨‍🎓 Õpilane
1. Ava **[Opilase-juhend.md](./Opilase-juhend.md)** ja järgi samme 0–8.
2. Veebiliidese detailid leiad vajadusel [BloodHound-CE-kasutusjuhend.md](./BloodHound-CE-kasutusjuhend.md)-st.
3. Lahenduskäiku vaata alles siis, kui oled ise proovinud.

---

## Keskkond (vaikeväärtused)

- Domeen `hkhk.local` · DC `SERVER.hkhk.local` (`10.0.30.10`)
- Ründejaam Kali Linux · tööjaam Windows 11 (domeenis)
- Analüüs: BloodHound CE (`http://localhost:8080`)
- Stardikonto: `jaan.tamm` / `Suvi2026!`

> `Seed-ADLabor.ps1` tuvastab domeeni ise (`Get-ADDomain`) ja töötab ka muu `.local` nimega (nt `perenimi.local`) — ülaltoodud väärtused on näited ühest läbimängust.

---

## Õpiväljundid

Pärast laborit oskab õppija: lugeda AD õiguste graafi BloodHoundis; leida ja ära kasutada kuut levinud väärseadistust; selgitada iga ründetee loogikat (*miks* see töötab); ning kirjeldada parandust ja logipõhist avastamist (kaitsja vaade).
