from pymongo import MongoClient
import json

client = MongoClient("mongodb://localhost:27017/")
db = client["schronisko"]

with open("nierelacyjna_baza_danych.json", "r", encoding="utf-8") as f:
    database = json.load(f)

for collection_name, documents in database.items():
    collection = db[collection_name]
    collection.insert_many(documents)

print("Dane zostały zaimportowane do MongoDB.")
