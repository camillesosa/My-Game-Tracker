import time
import requests
import json as JSON
import mysql.connector


def main():
    URL = "https://api.mobygames.com/v1/games?api_key="
    api_key = "moby_sdCDybSPYjxpemBsRXkaWEHTbpY"

    # Create JSON object
    game = {"id": "", "title": "", "genre": "", "cover_url": ""}

    # Create JSON file
    with open("dbbuilder.sql", "w") as outfile:
        outfile.write("")

    index = 0

    while index < 15000:
        res = requests.get(URL + api_key + "&offset=" + str(index))
        data = res.json()
        games = data["games"]
        for g in games:
            game["id"] = g["game_id"]
            game["title"] = g["title"]
            game["genre"] = g["genres"][0]["genre_name"]
            try:
                game["cover_url"] = g["sample_cover"]["image"]
            except:
                game["cover_url"] = "nocover.png"

            # Insert into VideoGame table
            with open("dbbuilder.sql", "a") as outfile:
                # If game title has an apostrophe, escape it
                if "'" in game["title"]:
                    game["title"] = game["title"].replace("'", "''")
                try:
                    s = (
                        "\nINSERT into VideoGame(game_id, genre, title, coverArt) VALUES ("
                        + str(game["id"])
                        + ", "
                        + "'"
                        + game["genre"]
                        + "'"
                        + ", "
                        + "'"
                        + game["title"]
                        + "'"
                        + ", "
                        + "'"
                        + game["cover_url"]
                        + "'"
                        + ");"
                    )
                    outfile.write(s)
                except Exception:
                    print("Error writing to file")

            print(game["id"])

        index += 100
        # sleep 10 seconds
        time.sleep(10)


if __name__ == "__main__":
    main()
