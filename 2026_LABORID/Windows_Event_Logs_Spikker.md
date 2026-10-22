# Windows Serveri ja Active Directory turvalogide spikker

See õppematerjal annab ülevaate kõige kriitilisematest sündmuste ID-dest (Event ID), mida tuleb jälgida, et tuvastada ründeid (nagu PrintNightmare) ja loata tegevusi Active Directory keskkonnas. See on abimaterjal küberkaitse laborite ja E-ITS logihalduse teemade juurde.

## 1. Print Spooler teenuse logid (PrintNightmare indikaatorid)
**Asukoht (Event Viewer):** `Applications and Services Logs -> Microsoft -> Windows -> PrintService`
*(Märkus: Operational logi on vaikimisi keelatud ja see tuleb administraatoril turvaintsidentide tuvastamiseks käsitsi sisse lülitada).*

* **Event ID 808 (Draiveri laadimise tõrge):** `spoolsv.exe` üritas laadida DLL-faili, mis ei läbinud süsteemi kontrolli. Ründe puhul viitab failitee sageli ebatavalisele asukohale või võrgujagale (nt `\\192.168.50.20\jaga\payload.dll`).
* **Event ID 809 (Turvalisuse valideerimise tõrge):** Spooler keeldus laadimast allkirjastamata või kahtlast DLL-i. See viitab tihti ebaõnnestunud ründekatsele.
* **Event ID 316 (Uue draiveri laadimine):** Näitab, et Print Spooler laadis edukalt uue draiveri. Pahaloomulise tegevuse (eduka PrintNightmare ründe) korral kuvatakse siin ründaja spetsiaalselt koostatud kooditee.
* **Event ID 310 (Printeridraiveri lisamine):** Registreerib `RpcAddPrinterDriverEx` funktsiooni käivitamise. Seda funktsiooni kuritarvitatakse, et sundida süsteemi ründaja koodi laadima.

---

## 2. Windows Security turvalogid (Privileegide eskaleerimine ja tagauksed)
**Asukoht (Event Viewer):** `Windows Logs -> Security`

* **Event ID 4720 (Kasutajakonto loomine):** Süsteemi lisati uus kasutaja. Kui see sündmus toimub süsteemse konto `SYSTEM` (`S-1-5-18`) poolt vahetult pärast Spooleri anomaaliat, on see väga tugev indikaator, et ründaja on loonud süsteemi tagaukse.
* **Event ID 4724 (Parooli lähtestamine):** Keegi püüdis muuta teise kasutaja parooli (Reset password). See on kriitiline indikaator ACL (Access Control List) väärkonfiguratsioonide ärakasutamisel – näiteks siis, kui madalate õigustega tavakasutaja muudab ootamatult domeeni administraatori parooli.
* **Event ID 4728 (Turvagrupi liikmelisuse muutmine):** Kasutaja lisati turvagruppi. Tuleb alati uurida, kui sihtgrupiks on kõrgete privileegidega grupid nagu *Domain Admins*, *Enterprise Admins* või *Administrators*.
* **Event ID 5145 (Üksikasjalik võrgujaga pöördumine):** Näitab, kui server (näiteks domeenikontroller) pöördub üle SMB protokolli teise masina jagatud kausta poole. PrintNightmare ründe puhul pöördub DC ründaja Kali Linuxi masina poole, et laadida alla pahaloomuline kasulik laadung.
* **Event ID 7045 (Uue teenuse loomine):** Süsteemi paigaldati uus teenus. Ründajad loovad sageli uusi teenuseid, et tagada süsteemis püsivus (persistence) isegi pärast serveri taaskäivitamist.

---

## Kuidas logisid kaitsta ja analüüsida?

Ründaja, kes saavutab Windows Serveris SYSTEM või Administrator õigused, proovib esimese asjana kohalikud logid kustutada (näiteks käsuga `wevtutil cl Security`), et varjata oma tegevuse jälgi. 

E-ITS (Eesti infoturbestandard) nõuab ja parimad praktikad soovitavad, et kriitiliste serverite logid edastataks **reaalajas tsentraalsesse logihaldussüsteemi (SIEM)** – näiteks Wazuh, Elastic Stack või Splunk. Tsentraalses serveris on logid kaitstud kohaliku muutmise või kustutamise eest ning SIEM suudab erinevatest allikatest pärit sündmusi (nt Event ID 808 ja vahetult järgnev Event ID 4720) omavahel automaatselt korreleerida, tekitades turvameeskonnale viivitamatu häireteate.
