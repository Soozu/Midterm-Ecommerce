document.addEventListener('DOMContentLoaded', () => {
    const chatBox = document.getElementById('chat-box');
    const userInput = document.getElementById('user-input');

    userInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
});

function toggleChat() {
    const chatContainer = document.getElementById('chat-container');
    const openChatButton = document.getElementById('open-chat');
    if (chatContainer.style.display === 'none' || chatContainer.style.display === '') {
        chatContainer.style.display = 'flex';
        openChatButton.style.display = 'none';
    } else {
        chatContainer.style.display = 'none';
        openChatButton.style.display = 'block';
    }
}

function sendMessage() {
    const userInput = document.getElementById('user-input');
    const message = userInput.value.trim();
    if (message) {
        displayMessage('user', message);
        userInput.value = '';
        setTimeout(() => {
            getBotResponse(message);
        }, 1000);
    }
}

function displayMessage(sender, message) {
    const chatBox = document.getElementById('chat-box');
    const messageElement = document.createElement('div');
    messageElement.className = sender === 'bot' ? 'bot-message' : 'user-message';
    messageElement.textContent = message;
    chatBox.appendChild(messageElement);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function getBotResponse(message) {
    let response = 'I am sorry, I do not understand that.';

    const userMessage = message.toLowerCase();

    if (userMessage.includes('hello')) {
        response = 'Hello and welcome to Mabine\'s Store! How can I assist you today with our range of cosmetic makeup products?';
    } else if (userMessage.includes('foundation')) {
        response = 'I\'d be happy to help you with that! Could you please tell me your skin type (e.g., oily, dry, combination, sensitive) and the type of coverage you\'re looking for (e.g., light, medium, full)?';
    } else if (userMessage.includes('combination') && userMessage.includes('medium')) {
        response = 'Great choice! For combination skin and medium coverage, I recommend our "Mabine\'s Flawless Finish Foundation." It balances oily and dry areas, offering a smooth, natural look. Would you like to add it to your cart, or see similar options?';
    } else if (userMessage.includes('add to cart') || userMessage.includes('buy')) {
        response = 'Sure! Please click here to add the "Mabine\'s Flawless Finish Foundation" to your cart. Is there anything else I can assist you with today?';
    } else if (userMessage.includes('no') || userMessage.includes('that\'s all')) {
        response = 'You\'re welcome! If you have any more questions or need further assistance, feel free to chat with me anytime. Have a great day shopping at Mabine\'s Store!';
    } else if (userMessage.includes('products') || userMessage.includes('all products')) {
        response = 'We offer a wide range of products including Makeup and Skincare items. What specific product are you interested in?';
    } else if (userMessage.includes('problem') || userMessage.includes('issue')) {
        response = 'I am sorry to hear that you are facing issues. Can you please provide more details about the problem?';
    } else if (userMessage.includes('recommend') || userMessage.includes('suggest')) {
        response = 'Sure! Our most popular product is "Mabine\'s Hydrating Serum," which has the highest rating from our customers. Would you like to know more about it or see other top-rated products?';
    } else if (userMessage.includes('top rated') || userMessage.includes('best seller')) {
        response = 'Our best-selling product is "Mabine\'s Radiant Glow Cream." It\'s loved by many for its brightening and moisturizing effects. Would you like to add it to your cart or see customer reviews?';
    } else if (userMessage.includes('discount') || userMessage.includes('sale')) {
        response = 'We have ongoing discounts on various products! Currently, you can get a 20% discount on all our skincare products. Would you like to check them out?';
    } else if (userMessage.includes('mascara') || userMessage.includes('lipstick')) {
        response = 'Our best-selling mascara is "Mabine\'s Volumizing Mascara" and our top-rated lipstick is "Mabine\'s Matte Lipstick." Both are highly recommended by our customers. Would you like to add them to your cart or see other options?';
    }

    displayMessage('bot', response);
}