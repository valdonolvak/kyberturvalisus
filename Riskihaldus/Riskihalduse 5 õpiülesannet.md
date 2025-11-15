
# ✅ **5 RISKIANALÜÜSI ÕPIÜLESANNET**


## **Ülesanne 1 – Virtualiseerimiskeskkonna (Proxmox) riskide hindamine**

Analüüsi Proxmoxi ja virtuaalmasinate keskkonna riske:

1. Ressursipuudus (RAM/CPU/disk).
2. RAID1 ketta riknemine.
3. Snapshotide vale kasutus.
4. VLAN ja võrguliikluse riskid.
5. Hypervisori tarkvaravead.

Kirjelda:

* Milliseid teenuseid see risk mõjutab.
* Kuidas seda riski tuvastada (logid, monitor, SIEM).
* Milline on toimiv leevendus.

---

## **Ülesanne 2 – Veebiserverite (veeb1, veeb2, koormusjaotur) riskianalüüs**

Tuvasta vähemalt 12 riski, mis on seotud:

* koormusjaoturi (HAProxy) valekonfiguratsiooniga,
* WordPressi ja PHP haavatavustega,
* andmebaasiga suhtlemisega,
* SSH võtmetega,
* pluginatega,
* varunduse puudumisega.

Iga riski kohta:

* tõenäosus
* mõju
* leevendus
* kuidas testida leevenduse toimivust

---

## **Ülesanne 3 – Võrgu ja VLAN-ide riskiregister**

Koosta riskiregister, mis hõlmab:

* Fortigate konfiguratsiooni riske
* Cisco switching riske
* Ubiquiti WiFi riske
* DHCP snooping rikete riske
* Võõras klient VLAN200 võrgus

Lisa vähemalt 3 ründestsenaariumi, näiteks:

* VLAN hopping rünnak
* Rogue DHCP server
* Deauth Wi-Fi rünnak

Kõigi kohta:

* mõju teenustele
* parandused
* tuvastamise meetodid

---

## **Ülesanne 4 – Varunduse ja paroolihalduse riskianalüüs**

Analüüsi riske:

* Docker-keskkonna rikked
* Paroolihalduri (Vaultwarden, Bitwarden, Passbolt vms) haavatavused
* KeePassi offline koopiate lekked
* Varukoopiate korruptsioon
* Backupi testimise puudumine

Loo leevenduskava sh:

* backup rotation policy
* paroolipoliitikad
* õiguste mudel
* auditilogid

---

## **Ülesanne 5 – Muudatuste halduse riskid (Change Management)**

Koosta muudatuste halduse riskianalüüs järgmiste olukordade jaoks:

1. GPO muudatus, mis katkestab klientide töö.
2. Vale konfigureeritud firewall reegel Fortigate’is.
3. Vale DNS kirje.
4. Vale Ansible playbook, mis uuendab *kõik serverid korraga*.
5. Vale VLAN rakendamine Cisco kommutaatoris.
6. Proxmoxi kernel uuendus, mis rikub bootimise.

Selgita iga juhtumi puhul:

* kuidas risk realiseerub
* kuidas seda ennetada
* kuidas aru saada, et muutus läks valesti
* kuidas taastada süsteem

---

# ✅ **Samm-sammuline NÄIDISÜLESANNE lahendusega**

Näidisülesanne on leitav aadresstil:
https://github.com/valdonolvak/kyberturvalisus-privaatne/blob/main/P%C3%B5hjalik%20samm-sammuline%20N%C3%84IDIS%C3%9CLESANNE%20-%20riskihaldus.md 

---
