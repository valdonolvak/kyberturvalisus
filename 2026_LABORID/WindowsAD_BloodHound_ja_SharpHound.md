BloodHoundi ja SharpHoundi lisamine laborisse on suurepärane viis näidata õpilastele, kuidas ründajad näevad Active Directory keskkonda mitte nimekirjana, vaid omavahel seotud graafina (ründeteedena). See teeb ACL (Access Control List) väärkonfiguratsioonide, nagu `ForceChangePassword`, leidmise automatiseerituks ja visuaalseks.

Siin on samm-sammuline juhend, kuidas seda laborit täiendada:

1. **Ründaja tööriistade allalaadimine:** SharpHound andmete kogumiseks.
Kali Linuxis (või muus ründaja masinas) tuleb esmalt alla laadida SharpHound (BloodHoundi andmekoguja, Ingestor) ja Neo4j andmebaas.

1. Ava Kalis terminal ja paigalda Neo4j ning BloodHound, kui neid veel pole:

```bash
sudo apt update
sudo apt install neo4j bloodhound

```

2. Laadi GitHubist alla SharpHound (kuna labori eesmärk on rünnata üle võrgu, vajame Pythoni põhiseid tööriistu, nt BloodHound.py, et ei peaks ründeprogrammi Windowsi masinasse viima):

```bash
sudo apt install bloodhound.py

```


2. **Andmete kogumine (Ingesting) AD-st:** Verekoer lastakse lahti.
Ründajal (õpilasel) on teada ainult madalate õigustega kasutaja `opilane1` parool. See on ainus, mida BloodHound vajab, et kogu domeenist lugeda kõikvõimalikke õigusi ja seoseid (Active Directory on vaikimisi väga jutukas).

Käivita Kalis terminalist Pythoni Ingestor:

```bash
bloodhound-python -u opilane1 -p 'Koolitus2026!' -d lab.local -ns 192.168.50.10 -c all

```

*Selgitus:* `-c all` käsib koguda kõik andmed (kasutajad, grupid, arvutid, ACL-id, seansid jne). Skript genereerib mitu JSON-faili (nt `users.json`, `groups.json`, `computers.json`), mis sisaldavad kogu Active Directory kaardistust.


3. **Andmebaasi ja BloodHoundi UI käivitamine:** Neo4j graafandmebaasi ettevalmistus.
Andmete visualiseerimiseks peame käivitama Neo4j teenuse ja seejärel BloodHoundi graafilise liidese.

1. Käivita Neo4j:

```bash
sudo neo4j start

```

2. Kui käivitad Neo4j esimest korda, mine veebilehitsejaga `http://localhost:7474`, logi sisse vaikimisi mandaatidega (`neo4j` / `neo4j`) ja muuda parool (nt `BloodHound2026!`).
3. Käivita BloodHound uues terminaliaknas (käsuga `bloodhound` või menüüst).
4. Logi BloodHoundi rakendusse sisse, sisestades Neo4j andmebaasi aadressi (tavaliselt `bolt://localhost:7687`) ja äsja loodud Neo4j parooli.


4. **Andmete import ja analüüs:** Ründeteede visualiseerimine.
Nüüd tuleb kogutud andmed JSON-failidest BloodHoundi importida.

1. Lohista kõik eelmises sammus genereeritud `.json` failid BloodHoundi graafilise liidese aknasse (või kasuta impordi nuppu).
2. BloodHound töötleb andmed Neo4j andmebaasis läbi.
3. **Ründeteede otsimine:**
* Otsinguribale trüki kõrge väärtusega sihtmärk: `IT_ADMIN@LAB.LOCAL` ja klõpsa tema nimele.
* Parempoolsest menüüst vali **Node Info** ja otsi altpoolt valikut "Inbound Object Control" -> **Explicit Object Controllers**. See näitab, kes omab selle konto üle otsest kontrolli.
* Teine ja visuaalselt parem viis: Otsinguribale trüki `OPILANE1@LAB.LOCAL`. Tee tema nimel paremklõps ja vali **Mark User as Owned** (märgi kasutaja kompromiteerituks).
* Seejärel otsi üles *Domain Admins* grupp, tee paremklõps ja vali **Shortest Path from Owned Nodes**.




5. **Visuaalne tulemus:** Punkti ühendamine rünnakuga.
BloodHound joonistab nüüd ekraanile selge graafi:

`OPILANE1@LAB.LOCAL`  --> [joon, millel on kirjas **ForceChangePassword**] --> `IT_ADMIN@LAB.LOCAL` --> [joon **MemberOf**] --> `DOMAIN ADMINS@LAB.LOCAL`

Õpilane näeb visuaalselt, et kuigi ta on tavakasutaja, eksisteerib AD konfiguratsioonis otsetee (õiguste ülekirjutamise näol), mis viib domeeni administraatoriks saamiseni.
