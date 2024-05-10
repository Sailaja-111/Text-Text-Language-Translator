import requests

class EasyGoogleTranslate:
    def __init__(self, source_language='auto', target_language='fr', timeout=5):
        self.source_language = source_language
        self.target_language = target_language
        self.timeout = timeout
        self.base_url = 'https://api.mymemory.translated.net/get'

    def translate(self, text, source_language=None, target_language=None):
        if source_language:
            self.source_language = source_language
        if target_language:
            self.target_language = target_language
        
        try:
            params = {
                'q': text,
                'langpair': f'{self.source_language}|{self.target_language}',
                'key': '167e5f95bf4fe578d791'  # Your MyMemory API key
            }
            response = requests.get(self.base_url, params=params, timeout=self.timeout)
            response.raise_for_status()  # Raise exception for 4XX and 5XX status codes
            translated_text = response.json().get('responseData', {}).get('translatedText')
            return translated_text
        except requests.RequestException as e:
            return f"Translation failed: {e}"
