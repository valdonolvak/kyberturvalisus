---

## 4. ⚛️ Kvantkrüptograafia ja tuleviku turvalisus

Kvantkrüptograafia on laiem termin, mis hõlmab kahte peamist valdkonda, millest mõlemad kasutavad **kvantmehaanika** seadusi andmete kaitsmiseks ja krüptosüsteemide murdmiseks.

---

### A. Kvantkrüpteerimine (Quantum Cryptography)

**Kvantvõtme jaotus (Quantum Key Distribution ehk QKD)** on ainus kvantkrüptograafia valdkond, mis on tänapäeval juba reaalselt kasutatav.

#### Põhimõte: Fotonid ja saladus
* QKD ei krüpteeri tegelikke sõnumeid, vaid loob **üli-turvalise krüpteerimisvõtme**, kasutades footoneid (valgusosakesi).
* Footonid saadetakse läbi optilise kiu ja iga footon kannab endas **kvantolekusse** kodeeritud bitti (0 või 1).
* **Turvalisus:** Kvantmehaanika põhimõte (Heisenbergi määramatuse printsiip) tagab, et **iga katse võtit pealt kuulata muudab paratamatult footonite kvantolekut**.
* **Avastamine:** Saatja ja vastuvõtja suudavad kohe tuvastada, et keegi on sidet pealt kuulanud, ning tühistavad võtme ja alustavad uuesti. See tagab **täiusliku ettekande turvalisuse** (forward secrecy).

#### Näide: BB84 protokoll
* Tuntuim QKD protokoll, mille leiutasid Charles Bennett ja Gilles Brassard.
* **Kasutus:** QKD-d kasutatakse andmete kaitsmiseks lühikestel kuni keskmistel kaugustel (nt pankade või valitsusasutuste vahel) ja see on **turvaline ka tulevaste kvantarvutite rünnakute vastu**.

---

### B. Kvantkindel krüptograafia (Post-Quantum Cryptography ehk PQC)

Kuigi QKD on turvaline, ei ole see lahendus kõikidele krüptoprobleemidele. **Post-Quantum Cryptography** keskendub uute **matemaatiliste algoritmide** loomisele, mida on võimalik käitada **tavalistes (klassikalistes) arvutites**, kuid mis on **vastupidavad kvantarvutite rünnakutele**.

#### Probleemi allikas: Shori algoritm
* Tänapäeval kasutatava asümmeetrilise krüptograafia (nt **RSA** ja **Elliptiliste Kõverate Krüptograafia ehk ECC**) turvalisus põhineb matemaatilistel probleemidel, mida on klassikaliste arvutitega *äärmiselt* raske lahendada (nt suurte arvude faktoreerimine).
* **Peter Shori kvantalgoritm** suudaks aga piisavalt suure kvantarvuti abil need probleemid lahendada **eksponentsiaalselt kiiremini**, murdes seega meie praegused avaliku võtme süsteemid.

#### PQC lahendused (PQC Candidates)
PQC algoritmid põhinevad keerulistel matemaatilistel probleemidel, mida isegi Shori algoritm ei suuda kiiresti lahendada. Need jagunevad erinevateks klassideks:

1.  **Võrgupõhine krüptograafia (Lattice-based cryptography):** Peamine suund (nt **NTRU, Kyber**). Selle turvalisus põhineb lühimate vektorite leidmise probleemil (SVP).
2.  **Koodipõhine krüptograafia (Code-based cryptography):** Kasutab veaparanduskoodide keerukust (nt **McEliece šiffer**).
3.  **Hajuspõhine krüptograafia (Hash-based cryptography):** Kasutab krüptograafilisi räsimisfunktsioone digitaalallkirjade loomiseks.

#### Standardiseerimine
* USA **Riiklik Standardite ja Tehnoloogia Instituut (NIST)** on teostanud ulatuslikku konkurssi, et valida välja uued **kvantkindlad standardid** (algoritmid, mis asendavad tulevikus RSA ja ECC). See standardimine on käimas ja on kriitiline samm üleminekuks uuele turvaajastule.

---
Kas sooviksite, et ma detailiseeriksin **Shori algoritmi mõju** RSA-le või seletaks, kuidas **võrgupõhine krüptograafia** töötab?
