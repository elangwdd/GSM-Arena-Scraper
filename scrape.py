import requests
from bs4 import BeautifulSoup

def get_phone_specifications(phone_model):
    # Get the HTML content of the page
    url = f"https://www.gsmarena.com/{phone_model}-3993.php"
    page = requests.get(url)
    soup = BeautifulSoup(page.content, "html.parser")

    # Find the table containing the specifications
    table = soup.find("table", class_="specs-table")

    # Extract the specifications
    specifications = {}
    for row in table.find_all("tr"):
        key = row.find("td", class_="ttl").text.strip()
        value = row.find("td", class_="nfo").text.strip()
        specifications[key] = value

    return specifications

# Example usage
phone_model = "samsung_galaxy_s21_5g"
specifications = get_phone_specifications(phone_model)
print(specifications)
