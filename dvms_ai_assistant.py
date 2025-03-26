from flask import Flask, request, jsonify
from flask_cors import CORS
from openai import OpenAI
import os
from dotenv import load_dotenv
import logging

# Load environment variables from .env file if available
load_dotenv()

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Initialize Flask app
app = Flask(__name__)

# Configure CORS (Allow all origins in development, restrict in production)
CORS(app, resources={r"/chat": {"origins": "*"}})  # Change "*" to allowed domain in production

# Get API key from environment variables
API_KEY = os.getenv("OPENAI_API_KEY")
if not API_KEY:
    logger.error("❌ OpenAI API key not found in environment variables")
    raise ValueError("❌ OpenAI API key is required. Please set OPENAI_API_KEY environment variable.")

# ✅ Initialize OpenAI Client Correctly
client = OpenAI(api_key=API_KEY)

@app.route('/chat', methods=['POST'])
def chat():
    """
    Handle chat requests from the frontend.
    Expects JSON payload with 'message' field.
    Returns AI response or error message.
    """
    try:
        # Validate request content type
        if not request.is_json:
            return jsonify({"error": "Request must be JSON"}), 415

        # Get user input
        data = request.get_json()
        user_input = data.get("message")
        
        if not user_input or not isinstance(user_input, str):
            return jsonify({"error": "Valid message string is required"}), 400

        # Log the request
        logger.info(f"📩 Received message: {user_input[:50]}...")

        # Get response from OpenAI
        response = client.chat.completions.create(
            model="gpt-3.5-turbo",  # Change to "gpt-4-turbo" if available
            messages=[
                {
                    "role": "system", 
                    "content": "You are an AI assistant for a Drug Verification and Management System."
                },
                {"role": "user", "content": user_input}
            ],
            max_tokens=500,  # Set a reasonable response length
            temperature=0.7  # Adjust for more or less randomness
        )

        # Extract AI response
        reply = response.choices[0].message.content.strip()
        logger.info("✅ Successfully generated response")
        
        return jsonify({"response": reply}), 200

    except Exception as e:
        logger.error(f"❌ Error processing request: {str(e)}")
        return jsonify({"error": "Internal server error"}), 500

# Error Handlers
@app.errorhandler(404)
def not_found(error):
    return jsonify({"error": "Not found"}), 404

@app.errorhandler(405)
def method_not_allowed(error):
    return jsonify({"error": "Method not allowed"}), 405

if __name__ == '__main__':
    # Get port from environment variable or default to 5000
    port = int(os.getenv("PORT", 5000))
    debug = os.getenv("FLASK_ENV", "production") == "development"
    
    logger.info(f"🚀 Starting server on port {port} in {'debug' if debug else 'production'} mode")
    app.run(
        host="0.0.0.0",
        port=port,
        debug=debug
    )
