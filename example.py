import requests

# Your MyMemory API key (replace 'YOUR_API_KEY' with your actual API key)
api_key = "167e5f95bf4fe578d791"

# Source text to be translated
source_text = "Hello, how are you?"

# Source and target languages
source_language = "en"
target_language = "de"  # Example: French

# API endpoint
url = "https://api.mymemory.translated.net/get"

# Request parameters
params = {
    "q": source_text,
    "langpair": f"{source_language}|{target_language}",
    "key": api_key
}

# Make the translation request
response = requests.get(url, params=params)

# Handle the response
if response.status_code == 200:
    data = response.json()
    if "responseData" in data:
        translated_text = data["responseData"]["translatedText"]
        print("Translated text:", translated_text)
    else:
        print("Translation not found.")
else:
    print("Translation failed. Error:", response.text)
