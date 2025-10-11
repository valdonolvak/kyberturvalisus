Uurime detailsemalt **NIST-i intsidentide käsitlemise mudelit** kui uurimismeetodit ning rakendame seda uue ja keerulise näite – **Stuxneti** – puhul.

---

## Uurimismeetod: NIST-i Intsidentide Käsitlemise Mudel

Küberintsidentide uurimisel on **NIST-i nelja faasi mudel** (põhineb dokumendil **NIST SP 800-61**) raamistik, mis aitab analüüsida intsidenti mitte ainult tagajärgede, vaid ka ennetuse ja järelduste vaatenurgast.

Uurimismetoodika seisneb selles, et valitud intsidendi (**Juhtumianalüüsi**) kõik aspektid vaadatakse läbi, kasutades nelja faasi küsimusi.

### Uurimisküsimused iga Faasi Järgi:

| Faas | Põhieesmärk | Uurimisküsimused Juhtumi Analüüsimisel |
| :--- | :--- | :--- |
| **1. Ettevalmistus** | Kas organisatsioon oli valmis? | **Mis oleks tulnud teha teisiti enne rünnakut?** Kas olid olemas piisavad varundused, poliitikad, parandused (patchid) ja koolitused? |
| **2. Tuvastamine** | Kuidas intsident avastati ja analüüsiti? | **Kuidas intsidenti reaalselt avastati?** Kas ründaja oli märkamatult tegutsenud? Millised logid või hoiatused viitasid rünnakule? |
| **3. Ohjeldamine** | Kuidas intsident peatati? | **Milliseid samme tehti rünnaku leviku peatamiseks?** Kas algpõhjus suudeti kiiresti eemaldada ja süsteemid taastada? Kui kaua taastusprotsess aega võttis? |
| **4. Järeltegevused** | Mida intsident õpetas? | **Millised olid intsidendi pikaajalised tagajärjed (finantsiline, maine)?** Milliseid konkreetseid turvameetmeid organisatsioon pärast juhtumit parendas? |

---

## Juhtumianalüüsi Näide: Stuxnet (2010) – Tööstusspionaaž ja Sabotaaž

Valime uurimiseks **Stuxneti** — keeruka küberrelva, mis oli suunatud Irani tuumaprogrammi tsentrifuugidele. See on hea näide, sest see toimus **väga spetsiifilises ja isoleeritud keskkonnas (OT/ICS)** ning sellel oli riiklik tähtsus.

### 1. Faas: Ettevalmistus 🛠️

**Eesmärk:** Hinnata sihtasutuse valmisolekut rünnakuks.

| Uurimistulemused (Stuxneti Kontekstis) | Järeldus |
| :--- | :--- |
| **Füüsiline isoleeritus:** Sihtvõrk oli **õhupiluga (air-gapped)** isoleeritud internetist. See näitab head *esmast* ettevalmistust. | **Ettevalmistus Tase: Keskmine (Aga Valedele Ohtudele).** Ettevalmistus oli suunatud välistele internetiohtudele, kuid mitte sisemistele sissetungidele. |
| **Süsteemide kaitsmine:** Kasutati standardseid Windowsi operatsioonisüsteeme ja Siemensi tarkvara, mis aga sisaldasid parandamata haavatavusi. | **Puudused Turvaparandustes:** Puudusid protseduurid või võimekus hoida isoleeritud süsteeme alati ajakohastena, mis lubas nelja erineva **nullpäeva haavatavuse** ärakasutamist. |
| **Andmekandja poliitika:** Tundub, et USB-mälupulkade kasutamise poliitika oli nõrk või ebaefektiivne, kuna Stuxnet levis just mälupulkade kaudu. | **Ligipääsukontrolli Viga:** Ei suudetud tagada, et isoleeritud võrku ei tooks sisse tundmatuid andmekandjaid. |

---

### 2. Faas: Tuvastamine ja Analüüs 🔎

**Eesmärk:** Kuidas rünnak avastati ja kuidas selle olemus paljastati.

| Uurimistulemused (Stuxneti Kontekstis) | Järeldus |
| :--- | :--- |
| **Avastamise allikas:** Intsidenti **ei avastanud** sihtasutus ise. Stuxnet pääses välja ja levis teistesse süsteemidesse, kus see avastati 2010. aasta juunis Valgevene turvafirma poolt. | **Tuvastamine: Ebaõnnestumine.** Stuxneti *tegelik eesmärk* (tsentrifuugide rikkumine) oli varjatud. See avastati alles *sekundaarse leviku* tõttu. |
| **Tuvastamise viis:** Rünnaku tagajärjed tuvastati tsentrifuugide ebanormaalsest käitumisest (kiiruse muutmine), mitte IT-turvasüsteemidest. | **Monitooringu Viga:** Turvameetmed keskendusid IT-le, mitte tööstuslike kontrollsüsteemide (PLC) ja füüsilise protsessi monitoorimisele. |
| **Rünnaku keerukus:** Analüüsi käigus paljastus, et tegu oli äärmiselt keeruka moodsa pahavaraga, mis suutis end maskeerida ning mis kasutas rünnakuks spetsiaalseid digitaalsertifikaate. | **Analüüs: Väga keeruline.** Tuvastati, et tegemist oli sihitud ja riiklikult toetatud küberrelvaga. |

---

### 3. Faas: Ohjeldamine, Kaotamine ja Taastamine 🛑

**Eesmärk:** Kuidas rünnak peatati ja süsteemid taastati.

| Uurimistulemused (Stuxneti Kontekstis) | Järeldus |
| :--- | :--- |
| **Ohjeldamine:** Stuxnet oli programmeeritud ründama väga spetsiifilisi tsentrifuugimudeleid, mistõttu ohjeldamine väljaspool sihtasutust (kui see avastati) oli suhteliselt lihtne – pahavara lihtsalt ei töötanud mujal. | **Looduslik Ohjeldamine:** Ründaja väga spetsiifiline siht tagas, et pärast avastamist ei kujutanud Stuxnet endast laialdast ohtu. |
| **Kaotamine:** Kaotamine nõudis kõigi nakatunud Windowsi ja Siemensi kontrollerite täielikku puhastamist ja uuesti installeerimist, lisaks pahavara poolt kasutatud nelja nullpäeva haavatavuse parandamist. | **Kaotamine: Aeganõudev ja Kallis.** Nõudis füüsiliste varuosade vahetamist (rikutud tsentrifuugid) ja keeruliste tööstussüsteemide kontrollereid. |
| **Taastamine:** Tsentrifuugide taastamine nõudis füüsilise infrastruktuuri parandamist/vahetamist ja tootmisprotsessi uuesti käivitamist. | **Taastumine: Pikaajaline.** Tuumaprogrammi pidi pikalt tagasi lükatama. |

---

### 4. Faas: Järeltegevused 📝

**Eesmärk:** Analüüsida õppetunde ja poliitikamuudatusi.

| Uurimistulemused (Stuxneti Kontekstis) | Järeldus |
| :--- | :--- |
| **Õppetunnid:** Stuxnet näitas, et *air-gap* pole imerohi ning et **OT (Operational Technology)** süsteemid vajavad eraldi turvapoliitikat, mis arvestab füüsiliste tagajärgedega. | **Poliitikamuudatused:** Järeldus oli selge vajadus eraldiseisvate OT-turvameetmete järele, sealhulgas range USB-seadmete kasutamise kontroll ja füüsilise protsessi anomaaliate monitooring. |
| **Tagajärjed:** Intsident tekitas tohutut finantskahju ja tehnilist tagasilööki, lükates tuumaprogrammi hinnanguliselt kaks aastat edasi. | **Õiguslikud Mõjud:** Tegu oli esimese teadaoleva küberrelvaga, mis põhjustas füüsilist kahju, mis tõi kaasa uued rahvusvahelised diskussioonid küberkonfliktide reeglite kohta. |
| **Teadlikkus:** Stuxnet aitas märkimisväärselt tõsta **kogu maailma tööstusettevõtete** teadlikkust OT-süsteemide haavatavusest. | **Globaalne Mõju:** Toimis äratuskellana infrastruktuuri küberkaitse alal. |
