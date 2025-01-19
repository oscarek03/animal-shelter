import pandas as pd
import json

files_and_sheets = {
    "zwierzeta.xlsx": "Export Worksheet",
    "adresy.xlsx": None,
    "darczyncy.xlsx": None,
    "kojce.xlsx": None,
    "pracownicy.xlsx": None,
    "pracownik_zwierzeta.xlsx": None,
    "rejestr_adopcji.xlsx": None,
    "rejestr_darowizn.xlsx": None
}


database = {}

try:
    for file, sheet in files_and_sheets.items():
        if sheet:
            data = pd.read_excel(file, sheet_name=sheet)
        else:
            data = pd.read_excel(file)

        for col in data.select_dtypes(include=["datetime64[ns]"]):
            data[col] = data[col].dt.strftime('%Y-%m-%d')

        collection_name = file.split('.')[0].upper()

        database[collection_name] = data.to_dict(orient="records")

    output_file = "nierelacyjna_baza_danych.json"
    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(database, f, ensure_ascii=False, indent=4)

    print(f"Baza danych nierelacyjna została zapisana w pliku {output_file}")

except Exception as e:
    print(f"Wystąpił błąd: {e}")
