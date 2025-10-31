// Använder IIFE för att undvika global scope förorening
(function() {
    // Konstanter för DOM-element och konfiguration
    const DOM = {
        chatBubble: document.getElementById('chat-bubble'),
        chatModal: document.getElementById('chatModal'),
        sendChatButton: document.getElementById('sendChatButton'),
        chatMessages: document.querySelector('#chatModal .modal-body'),
        chatInput: document.getElementById('chatInput')
    };

    const CONFIG = {
        typingDelay: 1000,
        errorMessage: 'Ett fel uppstod. Vänligen försök igen.',
        welcomeMessage: 'Hej och välkommen! Jag är PutsAssistenten, din AI-assistent för fönsterputsning. Jag kan hjälpa dig med information om våra tjänster, ge prisuppskattningar och svara på allmänna frågor om verksamheten och fönsterputsning. Vad skulle du vilja ha hjälp med?',
        errorMessages: [
            'Tyvärr kunde jag inte besvara din fråga just nu',
            'Det uppstod ett problem när jag försökte svara',
            'Jag kunde inte tolka svaret',
            'Ett oväntat fel inträffade',
            'Vänligen försök igen senare'
        ]
    };

    let chatModal;

    // Event listeners
    function setupEventListeners() {
        DOM.chatBubble.addEventListener('click', openModal);
        DOM.sendChatButton.addEventListener('click', sendMessage);
        DOM.chatInput.addEventListener('keydown', handleEnterKey);

        DOM.chatModal.addEventListener('shown.bs.modal', function () {
            if (DOM.chatMessages.children.length === 0) {
                loadChatHistory().then(() => {
                    scrollToBottom();
                });
            } else {
                scrollToBottom();
            }
        });

        DOM.chatModal.addEventListener('hidden.bs.modal', function () {
            document.body.style.overflow = '';
        });
    }

    let chatPromptShown = false;

    function showChatPrompt() {
        if (chatPromptShown) return;  // Förhindra att funktionen körs mer än en gång
        chatPromptShown = true;

        const chatPrompt = document.getElementById('chat-prompt');
        const chatBubble = document.getElementById('chat-bubble');

        // Sätt startposition och storlek
        gsap.set(chatPrompt, {
            scale: 0.5,
            opacity: 0,
            x: chatBubble.offsetWidth / 2,
            transformOrigin: 'right center'
        });

        // Animera fram chattbubblan
        const tl = gsap.timeline({onComplete: hideChatPrompt});

        tl.to(chatPrompt, {
            duration: 0.5,
            scale: 1,
            opacity: 1,
            x: 0,
            ease: "power2.out"
        });

        function hideChatPrompt() {
            setTimeout(() => {
                gsap.to(chatPrompt, {
                    duration: 0.5,
                    scale: 0.5,
                    opacity: 0,
                    x: chatBubble.offsetWidth / 2,
                    ease: "power2.in",
                    onComplete: () => {
                        gsap.set(chatPrompt, {clearProps: "all"});
                        chatPromptShown = false;  // Återställ flaggan så att den kan visas igen om det behövs
                    }
                });
            }, 3000);
        }
    }

    // Öppna modalen
    function openModal() {
        document.body.style.overflow = 'hidden';
        chatModal.show();
    }

    function scrollToBottom(smooth = false) {
        requestAnimationFrame(() => {
            const modalBody = document.querySelector('#chatModal .modal-body');
            modalBody.scrollTo({
                top: modalBody.scrollHeight,
                behavior: smooth ? 'smooth' : 'auto'
            });
        });
    }

    function scrollToElement(element, smooth = false) {
        requestAnimationFrame(() => {
            const modalBody = document.querySelector('#chatModal .modal-body');
            const elementPosition = element.offsetTop - modalBody.offsetTop;
            modalBody.scrollTo({
                top: elementPosition,
                behavior: smooth ? 'smooth' : 'auto'
            });
        });
    }

    // Hantera Enter-tangenten
    function handleEnterKey(event) {
        if (event.key === 'Enter' && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    }

    // Kontrollera om strängen innehåller någon av felmeddelande-fraserna
    function isErrorMessage(message) {
        if (!message || typeof message !== 'string') {
            return false;
        }

        return CONFIG.errorMessages.some(errorPhrase =>
            message.toLowerCase().includes(errorPhrase.toLowerCase())
        );
    }

    // Lägg till denna funktion utanför sendMessage
    function isLocalStorageAvailable() {
        try {
            const test = '__test__';
            localStorage.setItem(test, test);
            localStorage.removeItem(test);
            return true;
        } catch(e) {
            return false;
        }
    }

    let sessionStorage = {
        getItem: function(key) {
            if (isLocalStorageAvailable()) {
                return localStorage.getItem(key);
            }
            return this[key];
        },
        setItem: function(key, value) {
            if (isLocalStorageAvailable()) {
                localStorage.setItem(key, value);
            } else {
                this[key] = value;
            }
        }
    };

    function getOrCreateSessionId() {
        // Check session storage first with fallback
        let sessionId = sessionStorage.getItem('chat_session_id');
        
        // If no session ID exists, create a new one with domain information embedded
        if (!sessionId) {
            // Get full domain (e.g., puts-i-karlstad.putsamer.se)
            const domain = window.location.hostname;
            
            // Create a structured session ID that embeds the domain
            // Format: session_domainname_randomstring
            // This ensures the domain is captured even if the request context is lost
            const randomId = Math.random().toString(36).substring(2, 11);
            sessionId = `session_${domain}_${randomId}`;
            
            // Store in both session storage and local storage as backup
            sessionStorage.setItem('chat_session_id', sessionId);
            localStorage.setItem('chat_session_id_backup', sessionId);
        }
        
        return sessionId;
    }

    async function sendMessage() {
        const userInput = DOM.chatInput.value.trim();
        if (userInput === '') return;

        const originalQuestion = userInput;
        appendMessage(userInput, 'user');
        DOM.chatInput.value = '';
        showTypingIndicator();

        const sessionId = getOrCreateSessionId();

        try {
            const response = await fetch('/ai-assistant/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: userInput,
                    session_id: sessionId
                })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const data = await response.json();

            if (data.status === 'processing') {
                await checkAIResponse(data.job_id, originalQuestion);
            } else {
                throw new Error('Unexpected response');
            }
        } catch (error) {
            console.error('Error:', error);
            hideTypingIndicator();
            appendMessage(CONFIG.errorMessage, 'error');
            DOM.chatInput.value = originalQuestion;
        }
    }

    // Kontrollera AI-svar
    async function checkAIResponse(jobId, originalQuestion) {
        const maxAttempts = 60;
        const delay = 1000;

        for (let i = 0; i < maxAttempts; i++) {
            try {
                const response = await fetch(`/ai-assistant/get-response?job_id=${jobId}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new TypeError("Oops, we haven't got JSON!");
                }

                const data = await response.json();

                if (data.response) {
                    hideTypingIndicator();
                    if (typeof data.response === 'string') {
                        // Kontrollera om svaret innehåller någon av felmeddelande-fraserna
                        if (isErrorMessage(data.response)) {
                            appendMessage(data.response, 'error');
                            DOM.chatInput.value = originalQuestion; // Återställ originalfrågan vid fel
                        } else {
                            appendMessage(data.response, 'ai');
                        }
                    } else if (typeof data.response === 'object' && data.response.error) {
                        appendMessage(data.response.error, 'error');
                        DOM.chatInput.value = originalQuestion; // Återställ originalfrågan vid fel
                    } else {
                        appendMessage('Ett oväntat svar mottogs från servern.', 'error');
                        DOM.chatInput.value = originalQuestion; // Återställ originalfrågan vid oväntat svar
                    }
                    return;
                } else if (data.status === 'processing') {
                    // Fortsätt vänta
                } else {
                    console.log('Unexpected response structure:', data);
                }
            } catch (error) {
                console.error('Error checking AI response:', error);
            }

            await new Promise(resolve => setTimeout(resolve, delay));
        }

        hideTypingIndicator();
        appendMessage('Timeout: Kunde inte hämta svar från AI. Vänligen försök igen.', 'error');
        DOM.chatInput.value = originalQuestion; // Återställ originalfrågan vid timeout
    }

    // Visa skrivindikator
    function showTypingIndicator() {
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'typing-indicator';
        typingIndicator.innerHTML = '<span></span><span></span><span></span>';
        DOM.chatMessages.appendChild(typingIndicator);
        scrollToBottom();
    }

    // Dölj skrivindikator
    function hideTypingIndicator() {
        const typingIndicator = document.querySelector('.typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    // Lägg till meddelande i chatten
    function appendMessage(message, sender) {
        const messageWrapper = document.createElement('div');
        messageWrapper.className = `message ${sender}`;

        if (sender === 'ai') {
            const avatar = document.createElement('div');
            avatar.className = 'ai-avatar';
            const img = document.createElement('img');
            img.src = '/assets/images/chatbot.png';
            img.alt = 'AI Avatar';
            avatar.appendChild(img);
            messageWrapper.appendChild(avatar);
        }

        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';

        if (sender === 'ai' || sender === 'error') {
            messageContent.innerHTML = DOMPurify.sanitize(message);
        } else {
            messageContent.textContent = message;
        }

        messageWrapper.appendChild(messageContent);
        DOM.chatMessages.appendChild(messageWrapper);

        if (sender === 'user') {
            scrollToBottom(); // Omedelbar scroll till botten för användarmeddelanden
        } else if (sender === 'ai') {
            // Scrolla först till botten omedelbart
            scrollToBottom();
            // Vänta en kort stund och scrolla sedan mjukt till toppen av AI-svaret
            setTimeout(() => {
                scrollToElement(messageWrapper, true);
            }, 100);
        }
    }

    // Initialisering
    async function loadChatHistory() {
        try {
            const sessionId = sessionStorage.getItem('chat_session_id');

            if (!sessionId) {
                console.log('No session ID found, initializing new chat');
                appendMessage(CONFIG.welcomeMessage, 'ai');
                scrollToBottom();
                return;
            }

            const response = await fetch(`/ai-assistant/chat-history?session_id=${sessionId}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const history = await response.json();

            if (history.error) {
                console.warn('Error loading chat history:', history.error);
                appendMessage(CONFIG.welcomeMessage, 'ai');
                scrollToBottom();
                return;
            }

            DOM.chatMessages.innerHTML = '';
            appendMessage(CONFIG.welcomeMessage, 'ai');

            history.forEach(item => {
                if (item.answer !== CONFIG.welcomeMessage) {
                    appendMessage(item.question, 'user');
                    appendMessage(item.answer, 'ai');
                }
            });

            scrollToBottom();
        } catch (error) {
            console.error('Error loading chat history:', error);
            appendMessage(CONFIG.welcomeMessage, 'ai');
            scrollToBottom();
        }
        return Promise.resolve();
    }

    // Uppdatera init-funktionen
    function init() {
        chatModal = new bootstrap.Modal(DOM.chatModal);
        setupEventListeners();

        const chatMessagesObserver = new MutationObserver(() => {
            scrollToBottom();
        });

        chatMessagesObserver.observe(DOM.chatMessages, { childList: true, subtree: true });

        // Visa chat-prompten efter 3 sekunder
        setTimeout(showChatPrompt, 5000);
    }

    // Kör initialisering när DOM är redo
    document.addEventListener('DOMContentLoaded', init);
})();