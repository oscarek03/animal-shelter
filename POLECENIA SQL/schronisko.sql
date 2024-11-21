--------------------------------------------------------
--  File created - czwartek-listopada-21-2024   
--------------------------------------------------------
--------------------------------------------------------
--  DDL for Sequence ADRESY_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SCHRONISKO"."ADRESY_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 51 CACHE 20 NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence PRACOWNICY_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SCHRONISKO"."PRACOWNICY_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 11 CACHE 20 NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence REJESTR_ADOPCJI_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SCHRONISKO"."REJESTR_ADOPCJI_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 5 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence SEQ_ADOPCJE_ID
--------------------------------------------------------

   CREATE SEQUENCE  "SCHRONISKO"."SEQ_ADOPCJE_ID"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 13 NOCACHE  NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Sequence ZWIERZETA_SEQ
--------------------------------------------------------

   CREATE SEQUENCE  "SCHRONISKO"."ZWIERZETA_SEQ"  MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 34 CACHE 20 NOORDER  NOCYCLE  NOKEEP  NOSCALE  GLOBAL ;
--------------------------------------------------------
--  DDL for Table ADRESY
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."ADRESY" 
   (	"ID_ADRESU" NUMBER, 
	"MIASTO" VARCHAR2(50 BYTE), 
	"KOD_POCZTOWY" VARCHAR2(10 BYTE), 
	"ULICA" VARCHAR2(100 BYTE), 
	"NUMER_DOMU" VARCHAR2(10 BYTE), 
	"NUMER_MIESZKANIA" VARCHAR2(10 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table DARCZYNCY
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."DARCZYNCY" 
   (	"ID" NUMBER, 
	"NAZWA_UZYTKOWNIKA" VARCHAR2(50 BYTE), 
	"IMIE" VARCHAR2(50 BYTE), 
	"NAZWISKO" VARCHAR2(50 BYTE), 
	"MAIL" VARCHAR2(100 BYTE)
   ) SEGMENT CREATION DEFERRED 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table KOJCE
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."KOJCE" 
   (	"KOJEC_ID" NUMBER, 
	"WIELKOSC" VARCHAR2(20 BYTE), 
	"NUMER" VARCHAR2(10 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table PRACOWNICY
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."PRACOWNICY" 
   (	"ID" NUMBER, 
	"IMIE" VARCHAR2(50 BYTE), 
	"NAZWISKO" VARCHAR2(50 BYTE), 
	"PENSJA" NUMBER(10,2), 
	"STANOWISKO" VARCHAR2(100 BYTE), 
	"ADRES_ID" NUMBER, 
	"DATA_ZATRUDNIENIA" DATE
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table PRACOWNIK_ZWIERZETA
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."PRACOWNIK_ZWIERZETA" 
   (	"PRACOWNIK_ID" NUMBER, 
	"ZWIERZE_ID" NUMBER
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table REJESTR_ADOPCJI
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."REJESTR_ADOPCJI" 
   (	"ID_ADOPCJI" NUMBER, 
	"DATA_ADOPCJI" DATE, 
	"ZWIERZE_ID" NUMBER, 
	"PRACOWNIK_ID" NUMBER, 
	"IMIE" VARCHAR2(100 BYTE), 
	"NAZWISKO" VARCHAR2(100 BYTE), 
	"ADRES_ID" NUMBER, 
	"NUMER_TELEFONU" VARCHAR2(15 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table REJESTR_DAROWIZN
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."REJESTR_DAROWIZN" 
   (	"ID" NUMBER, 
	"DARCZYNCA_ID" NUMBER, 
	"KWOTA" NUMBER(10,2), 
	"DATA" DATE
   ) SEGMENT CREATION DEFERRED 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Table ZWIERZETA
--------------------------------------------------------

  CREATE TABLE "SCHRONISKO"."ZWIERZETA" 
   (	"ID" NUMBER, 
	"IMIE" VARCHAR2(50 BYTE), 
	"RASA" VARCHAR2(50 BYTE), 
	"PLEC" VARCHAR2(20 BYTE), 
	"STATUS" VARCHAR2(50 BYTE), 
	"KOJEC_ID" NUMBER, 
	"DATA_PRZYJECIA" DATE, 
	"WIEK" NUMBER, 
	"TYP" VARCHAR2(50 BYTE)
   ) SEGMENT CREATION IMMEDIATE 
  PCTFREE 10 PCTUSED 40 INITRANS 1 MAXTRANS 255 
 NOCOMPRESS LOGGING
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
REM INSERTING into SCHRONISKO.ADRESY
SET DEFINE OFF;
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('1','Warszawa','00-001','Marsza³kowska','12','1');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('2','Kraków','30-001','Floriañska','25','2');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('3','Poznañ','60-001','Wielka','15','3');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('4','Wroc³aw','50-001','Œwidnicka','8','4');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('5','Gdañsk','80-001','D³uga','19','5');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('6','Szczecin','70-001','Pilsudskiego','3','6');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('7','Lublin','20-001','Lipowa','4','7');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('8','£ódŸ','90-001','Piotrkowska','11','8');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('9','Bydgoszcz','85-001','Chodkiewicza','22','9');
Insert into SCHRONISKO.ADRESY (ID_ADRESU,MIASTO,KOD_POCZTOWY,ULICA,NUMER_DOMU,NUMER_MIESZKANIA) values ('10','Katowice','40-001','Mickiewicza','5','10');
REM INSERTING into SCHRONISKO.DARCZYNCY
SET DEFINE OFF;
REM INSERTING into SCHRONISKO.KOJCE
SET DEFINE OFF;
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('1','Ma³y','K01DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('2','Œredni','K02DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('3','Du¿y','K03DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('4','Ma³y','K04DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('5','Œredni','K05DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('6','Du¿y','K06DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('7','Ma³y','K07DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('8','Œredni','K08DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('9','Du¿y','K09DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('10','Œredni','K10DOG');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('11','Ma³y','K11CAT');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('12','Œredni','K12CAT');
Insert into SCHRONISKO.KOJCE (KOJEC_ID,WIELKOSC,NUMER) values ('13','Du¿y','K13CAT');
REM INSERTING into SCHRONISKO.PRACOWNICY
SET DEFINE OFF;
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('1','Anna','Kowalska','4500','Weteryniarz','1',to_date('23/05/10','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('2','Piotr','Nowak','5000','Weteryniarz','2',to_date('22/11/23','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('3','Ewa','Zieliñska','2500','Sprz¹tacz','3',to_date('24/01/01','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('4','Krzysztof','Wójcik','2600','Sprz¹tacz','4',to_date('23/09/10','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('5','Monika','Lewandowska','3000','Opiekun Zwierz¹t','5',to_date('24/02/15','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('6','Tomasz','Szymañski','3200','Opiekun Zwierz¹t','6',to_date('23/06/12','RR/MM/DD'));
Insert into SCHRONISKO.PRACOWNICY (ID,IMIE,NAZWISKO,PENSJA,STANOWISKO,ADRES_ID,DATA_ZATRUDNIENIA) values ('8','Marek','Jankowski','6000','Koordynator Adopcji','8',to_date('22/08/05','RR/MM/DD'));
REM INSERTING into SCHRONISKO.PRACOWNIK_ZWIERZETA
SET DEFINE OFF;
REM INSERTING into SCHRONISKO.REJESTR_ADOPCJI
SET DEFINE OFF;
REM INSERTING into SCHRONISKO.REJESTR_DAROWIZN
SET DEFINE OFF;
REM INSERTING into SCHRONISKO.ZWIERZETA
SET DEFINE OFF;
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('1','Ares','Mieszaniec','Male','Adoptowany','1',to_date('24/11/21','RR/MM/DD'),'3','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('2','Rex','Owczarek niemiecki','Male','Dostêpny','2',to_date('24/10/15','RR/MM/DD'),'5','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('3','Azor','Mieszaniec','Male','Dostepny','3',to_date('24/07/01','RR/MM/DD'),'4','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('4','Luna','Labrador','Female','Dostepny','4',to_date('24/09/05','RR/MM/DD'),'2','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('5','Max','Mieszaniec','Male','Dostepny','5',to_date('22/09/16','RR/MM/DD'),'1','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('6','Bella','Mieszaniec','Female','Dostepny','6',to_date('24/10/29','RR/MM/DD'),'6','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('7','Charlie','Beagle','Male','Dostepny','7',to_date('24/08/10','RR/MM/DD'),'3','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('8','Rocky','Mieszaniec','Male','Dostepny','8',to_date('24/02/20','RR/MM/DD'),'4','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('9','Daisy','Collie','Female','Dostepny','9',to_date('24/06/10','RR/MM/DD'),'2','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('10','Shadow','Mieszaniec','Male','Dostepny','10',to_date('24/10/02','RR/MM/DD'),'7','Pies');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('11','Mimi','Mieszañec','Female','Dostepny','11',to_date('24/10/01','RR/MM/DD'),'2','Kot');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('12','Tina','Mieszañec','Female','Dostepny','12',to_date('24/09/15','RR/MM/DD'),'3','Kot');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('13','Tommy','Mieszañec','Male','Dostepny','13',to_date('24/07/20','RR/MM/DD'),'4','Kot');
Insert into SCHRONISKO.ZWIERZETA (ID,IMIE,RASA,PLEC,STATUS,KOJEC_ID,DATA_PRZYJECIA,WIEK,TYP) values ('14','Lizak','Mieszaniec','Male','Dostepny','9',to_date('24/11/06','RR/MM/DD'),'13','Pies');
--------------------------------------------------------
--  DDL for Index SYS_C008274
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008274" ON "SCHRONISKO"."KOJCE" ("KOJEC_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008277
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008277" ON "SCHRONISKO"."ADRESY" ("ID_ADRESU") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008279
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008279" ON "SCHRONISKO"."DARCZYNCY" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008280
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008280" ON "SCHRONISKO"."DARCZYNCY" ("NAZWA_UZYTKOWNIKA") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008281
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008281" ON "SCHRONISKO"."DARCZYNCY" ("MAIL") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008284
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008284" ON "SCHRONISKO"."REJESTR_DAROWIZN" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008292
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008292" ON "SCHRONISKO"."PRACOWNICY" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008294
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008294" ON "SCHRONISKO"."ZWIERZETA" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008298
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008298" ON "SCHRONISKO"."REJESTR_ADOPCJI" ("ID_ADOPCJI") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008302
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008302" ON "SCHRONISKO"."PRACOWNIK_ZWIERZETA" ("PRACOWNIK_ID", "ZWIERZE_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008277
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008277" ON "SCHRONISKO"."ADRESY" ("ID_ADRESU") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008279
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008279" ON "SCHRONISKO"."DARCZYNCY" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008280
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008280" ON "SCHRONISKO"."DARCZYNCY" ("NAZWA_UZYTKOWNIKA") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008281
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008281" ON "SCHRONISKO"."DARCZYNCY" ("MAIL") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008274
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008274" ON "SCHRONISKO"."KOJCE" ("KOJEC_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008292
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008292" ON "SCHRONISKO"."PRACOWNICY" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008302
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008302" ON "SCHRONISKO"."PRACOWNIK_ZWIERZETA" ("PRACOWNIK_ID", "ZWIERZE_ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008298
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008298" ON "SCHRONISKO"."REJESTR_ADOPCJI" ("ID_ADOPCJI") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008284
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008284" ON "SCHRONISKO"."REJESTR_DAROWIZN" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Index SYS_C008294
--------------------------------------------------------

  CREATE UNIQUE INDEX "SCHRONISKO"."SYS_C008294" ON "SCHRONISKO"."ZWIERZETA" ("ID") 
  PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS" ;
--------------------------------------------------------
--  DDL for Procedure DODAJ_ADOPCJE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."DODAJ_ADOPCJE" (
    id_zwierzecia IN NUMBER,
    id_pracownika IN NUMBER,
    id_adresu IN NUMBER,
    data_adopcji IN DATE,
    imie IN VARCHAR2,
    nazwisko IN VARCHAR2,
    numer_telefonu IN VARCHAR2
) AS
BEGIN
    -- Wstawianie danych do tabeli REJESTR_ADOPCJI
    INSERT INTO REJESTR_ADOPCJI (
        ID_ADOPCJI,
        ZWIERZE_ID,
        PRACOWNIK_ID,
        ADRES_ID,
        DATA_ADOPCJI,
        IMIE,
        NAZWISKO,
        NUMER_TELEFONU
    ) VALUES (
        seq_adopcje_id.NEXTVAL, -- Automatyczne przypisywanie ID
        id_zwierzecia,
        id_pracownika,
        id_adresu,
        data_adopcji,
        imie,
        nazwisko,
        numer_telefonu
    );

    -- Zatwierdzenie transakcji
    COMMIT;

END;

/
--------------------------------------------------------
--  DDL for Procedure DODAJ_ADRES
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."DODAJ_ADRES" (
    p_miasto VARCHAR2,
    p_kod_pocztowy VARCHAR2,
    p_ulica VARCHAR2,
    p_numer_domu VARCHAR2,
    p_numer_mieszkania VARCHAR2
) AS
BEGIN
    INSERT INTO Adresy (
        ID_ADRESU, 
        MIASTO, 
        KOD_POCZTOWY, 
        ULICA, 
        NUMER_DOMU, 
        NUMER_MIESZKANIA
    )
    VALUES (
        Adresy_seq.NEXTVAL, 
        p_miasto, 
        p_kod_pocztowy, 
        p_ulica, 
        CASE WHEN p_numer_domu IS NULL OR p_numer_domu = '' THEN NULL ELSE p_numer_domu END, 
        CASE WHEN p_numer_mieszkania IS NULL OR p_numer_mieszkania = '' THEN NULL ELSE p_numer_mieszkania END
    );
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure DODAJ_PRACOWNIKA
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."DODAJ_PRACOWNIKA" (
    p_imie VARCHAR2,
    p_nazwisko VARCHAR2,
    p_pensja NUMBER,
    p_stanowisko VARCHAR2,
    p_adres_id NUMBER,
    p_data_zatrudnienia DATE
) AS
BEGIN
    INSERT INTO Pracownicy (ID, IMIE, NAZWISKO, PENSJA, STANOWISKO, ADRES_ID, DATA_ZATRUDNIENIA)
    VALUES (Pracownicy_seq.NEXTVAL, p_imie, p_nazwisko, p_pensja, p_stanowisko, p_adres_id, p_data_zatrudnienia);
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure DODAJ_ZWIERZE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."DODAJ_ZWIERZE" (
    p_imie          VARCHAR2,
    p_rasa           VARCHAR2,
    p_plec           VARCHAR2,
    p_status         VARCHAR2,
    p_kojec_id       NUMBER,
    p_data_przyjecia DATE,
    p_wiek           NUMBER,
    p_typ            VARCHAR2
) AS
BEGIN
    INSERT INTO ZWIERZETA (
        ID, IMIE, RASA, PLEC, STATUS, KOJEC_ID, DATA_PRZYJECIA, WIEK, TYP
    ) 
    VALUES (
        ZWIERZETA_SEQ.NEXTVAL, p_imie, p_rasa, p_plec, p_status, p_kojec_id, p_data_przyjecia, p_wiek, p_typ
    );
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure EDYTUJ_ADRES
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."EDYTUJ_ADRES" (
    p_id_adresu NUMBER,
    p_miasto VARCHAR2,
    p_kod_pocztowy VARCHAR2,
    p_ulica VARCHAR2,
    p_numer_domu VARCHAR2,
    p_numer_mieszkania VARCHAR2
) AS
BEGIN
    UPDATE Adresy
    SET MIASTO = p_miasto,
        KOD_POCZTOWY = p_kod_pocztowy,
        ULICA = p_ulica,
        NUMER_DOMU = p_numer_domu,
        NUMER_MIESZKANIA = p_numer_mieszkania
    WHERE ID_ADRESU = p_id_adresu;
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure EDYTUJ_PRACOWNIKA
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."EDYTUJ_PRACOWNIKA" (
    p_id NUMBER,
    p_imie VARCHAR2,
    p_nazwisko VARCHAR2,
    p_pensja NUMBER,
    p_stanowisko VARCHAR2,
    p_adres_id NUMBER,
    p_data_zatrudnienia DATE
) AS
BEGIN
    UPDATE Pracownicy
    SET IMIE = p_imie,
        NAZWISKO = p_nazwisko,
        PENSJA = p_pensja,
        STANOWISKO = p_stanowisko,
        ADRES_ID = p_adres_id,
        DATA_ZATRUDNIENIA = p_data_zatrudnienia
    WHERE ID = p_id;
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure EDYTUJ_ZWIERZE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."EDYTUJ_ZWIERZE" (
    p_id_zwierzecia NUMBER,
    p_imie VARCHAR2,
    p_rasa VARCHAR2,
    p_plec VARCHAR2,
    p_status VARCHAR2,
    p_kojec_id NUMBER,
    p_data_przyjecia DATE,
    p_wiek NUMBER,
    p_typ VARCHAR2
) AS
BEGIN
    UPDATE Zwierzeta
    SET IMIE = p_imie,
        RASA = p_rasa,
        PLEC = p_plec,
        STATUS = p_status,
        KOJEC_ID = p_kojec_id,
        DATA_PRZYJECIA = p_data_przyjecia,
        WIEK = p_wiek,
        TYP = p_typ
    WHERE ID = p_id_zwierzecia;
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure USUN_ADOPCJE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."USUN_ADOPCJE" (
    p_id NUMBER
) AS
BEGIN
    -- Usuwamy rekord z tabeli REJESTR_ADOPCJI na podstawie ID adopcji
    DELETE FROM REJESTR_ADOPCJI WHERE ID_ADOPCJI = p_id;

    -- Zatwierdzamy transakcjê
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure USUN_ADRES
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."USUN_ADRES" (
    p_id NUMBER
) AS
BEGIN
    DELETE FROM Adresy WHERE ID_ADRESU = p_id;
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure USUN_PRACOWNIKA
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."USUN_PRACOWNIKA" (
    p_id NUMBER
) AS
BEGIN
    DELETE FROM Pracownicy WHERE ID = p_id;
    COMMIT;
END;

/
--------------------------------------------------------
--  DDL for Procedure USUN_ZWIERZE
--------------------------------------------------------
set define off;

  CREATE OR REPLACE EDITIONABLE PROCEDURE "SCHRONISKO"."USUN_ZWIERZE" (
    p_id NUMBER
) AS
BEGIN
    DELETE FROM Zwierzeta WHERE ID = p_id;
    COMMIT;
END;

/
--------------------------------------------------------
--  Constraints for Table ADRESY
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."ADRESY" MODIFY ("MIASTO" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."ADRESY" MODIFY ("KOD_POCZTOWY" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."ADRESY" ADD PRIMARY KEY ("ID_ADRESU")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table DARCZYNCY
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."DARCZYNCY" MODIFY ("NAZWA_UZYTKOWNIKA" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."DARCZYNCY" ADD PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SCHRONISKO"."DARCZYNCY" ADD UNIQUE ("NAZWA_UZYTKOWNIKA")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS"  ENABLE;
  ALTER TABLE "SCHRONISKO"."DARCZYNCY" ADD UNIQUE ("MAIL")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table KOJCE
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."KOJCE" ADD PRIMARY KEY ("KOJEC_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table PRACOWNICY
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."PRACOWNICY" MODIFY ("IMIE" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."PRACOWNICY" MODIFY ("NAZWISKO" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."PRACOWNICY" ADD PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table PRACOWNIK_ZWIERZETA
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."PRACOWNIK_ZWIERZETA" ADD PRIMARY KEY ("PRACOWNIK_ID", "ZWIERZE_ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table REJESTR_ADOPCJI
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."REJESTR_ADOPCJI" MODIFY ("DATA_ADOPCJI" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."REJESTR_ADOPCJI" ADD PRIMARY KEY ("ID_ADOPCJI")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table REJESTR_DAROWIZN
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."REJESTR_DAROWIZN" MODIFY ("KWOTA" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."REJESTR_DAROWIZN" MODIFY ("DATA" NOT NULL ENABLE);
  ALTER TABLE "SCHRONISKO"."REJESTR_DAROWIZN" ADD PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Constraints for Table ZWIERZETA
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."ZWIERZETA" ADD PRIMARY KEY ("ID")
  USING INDEX PCTFREE 10 INITRANS 2 MAXTRANS 255 COMPUTE STATISTICS 
  STORAGE(INITIAL 65536 NEXT 1048576 MINEXTENTS 1 MAXEXTENTS 2147483645
  PCTINCREASE 0 FREELISTS 1 FREELIST GROUPS 1
  BUFFER_POOL DEFAULT FLASH_CACHE DEFAULT CELL_FLASH_CACHE DEFAULT)
  TABLESPACE "USERS"  ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PRACOWNICY
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."PRACOWNICY" ADD FOREIGN KEY ("ADRES_ID")
	  REFERENCES "SCHRONISKO"."ADRESY" ("ID_ADRESU") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table PRACOWNIK_ZWIERZETA
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."PRACOWNIK_ZWIERZETA" ADD FOREIGN KEY ("PRACOWNIK_ID")
	  REFERENCES "SCHRONISKO"."PRACOWNICY" ("ID") ENABLE;
  ALTER TABLE "SCHRONISKO"."PRACOWNIK_ZWIERZETA" ADD FOREIGN KEY ("ZWIERZE_ID")
	  REFERENCES "SCHRONISKO"."ZWIERZETA" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REJESTR_ADOPCJI
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."REJESTR_ADOPCJI" ADD FOREIGN KEY ("ZWIERZE_ID")
	  REFERENCES "SCHRONISKO"."ZWIERZETA" ("ID") ENABLE;
  ALTER TABLE "SCHRONISKO"."REJESTR_ADOPCJI" ADD FOREIGN KEY ("PRACOWNIK_ID")
	  REFERENCES "SCHRONISKO"."PRACOWNICY" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table REJESTR_DAROWIZN
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."REJESTR_DAROWIZN" ADD FOREIGN KEY ("DARCZYNCA_ID")
	  REFERENCES "SCHRONISKO"."DARCZYNCY" ("ID") ENABLE;
--------------------------------------------------------
--  Ref Constraints for Table ZWIERZETA
--------------------------------------------------------

  ALTER TABLE "SCHRONISKO"."ZWIERZETA" ADD FOREIGN KEY ("KOJEC_ID")
	  REFERENCES "SCHRONISKO"."KOJCE" ("KOJEC_ID") ENABLE;
