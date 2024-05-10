from flask import Flask, request, jsonify
from easy_google_translate import EasyGoogleTranslate
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes
translator = EasyGoogleTranslate()

@app.route('/translate', methods=['POST'])
def translate():
    # Ensure that the required data is present in the request
    if 'text' not in request.form or 'target_language' not in request.form or 'detected_language' not in request.form:
        return jsonify({'error': 'Incomplete request. Required parameters are missing.'}), 400
    
    input_text = request.form['text']
    target_language = request.form['target_language']
    detected_language = request.form['detected_language']

    try:
        # Translate the input text
        translated_text = translator.translate(input_text, source_language=detected_language, target_language=target_language)
        # Construct the response JSON object with translation information
        response = {
            'inputText': input_text,
            'detectedLanguage': detected_language,
            'targetLanguage': target_language,
            'translatedText': translated_text
        }
        return jsonify(response), 200
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)
