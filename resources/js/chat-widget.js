/**
 * AI Chat Widget
 *
 * En fast chat-widget i nedre högra hörnet som kommunicerar
 * med AI-assistenten för teknisk rådgivning.
 */

// Wait for Alpine to be available, then register the component
document.addEventListener('alpine:init', () => {
    Alpine.data('chatWidget', () => ({
            // State
            isOpen: false,
            sessionId: null,
            messages: [],
            inputMessage: '',
            isLoading: false,
            error: null,
            hasLoadedHistory: false,

            // Init
            init() {
                this.sessionId = this.getOrCreateSessionId();
                console.log('Chat widget initialized with session:', this.sessionId);
            },

            // Session ID management (stored in localStorage)
            getOrCreateSessionId() {
                let sessionId = localStorage.getItem('chat_session_id');
                if (!sessionId) {
                    sessionId = this.generateUUID();
                    localStorage.setItem('chat_session_id', sessionId);
                }
                return sessionId;
            },

            // Generate UUID v4
            generateUUID() {
                return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
                    const r = Math.random() * 16 | 0;
                    const v = c === 'x' ? r : (r & 0x3 | 0x8);
                    return v.toString(16);
                });
            },

            // Toggle chat window
            toggleChat() {
                this.isOpen = !this.isOpen;

                if (this.isOpen && !this.hasLoadedHistory) {
                    this.loadHistory();
                }

                // Focus input when opened
                if (this.isOpen) {
                    this.$nextTick(() => {
                        this.$refs.messageInput?.focus();
                    });
                }
            },

            // Load chat history
            async loadHistory() {
                try {
                    const response = await fetch(`/api/chat/history?session_id=${this.sessionId}`);
                    const data = await response.json();

                    if (data.success && data.history.length > 0) {
                        this.messages = data.history.map(chat => ([
                            { role: 'user', content: chat.question },
                            { role: 'assistant', content: chat.answer }
                        ])).flat();

                        this.hasLoadedHistory = true;
                        this.scrollToBottom();
                    }
                } catch (error) {
                    console.error('Failed to load chat history:', error);
                    // Inte kritiskt fel, fortsätt utan historik
                }
            },

            // Send message
            async sendMessage() {
                if (!this.inputMessage.trim() || this.isLoading) {
                    return;
                }

                const userMessage = this.inputMessage.trim();
                this.inputMessage = '';
                this.error = null;

                // Add user message to UI immediately
                this.messages.push({
                    role: 'user',
                    content: userMessage
                });

                this.scrollToBottom();
                this.isLoading = true;

                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            message: userMessage,
                            session_id: this.sessionId
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.error || 'Ett fel uppstod');
                    }

                    // Add AI response to UI
                    this.messages.push({
                        role: 'assistant',
                        content: data.response
                    });

                    this.scrollToBottom();

                } catch (error) {
                    console.error('Chat error:', error);
                    this.error = error.message || 'Kunde inte skicka meddelandet. Försök igen.';

                    // Remove user message if request failed
                    this.messages.pop();
                } finally {
                    this.isLoading = false;
                }
            },

            // Handle Enter key
            handleKeydown(event) {
                if (event.key === 'Enter' && !event.shiftKey) {
                    event.preventDefault();
                    this.sendMessage();
                }
            },

            // Scroll to bottom of messages
            scrollToBottom() {
                this.$nextTick(() => {
                    const messagesContainer = this.$refs.messagesContainer;
                    if (messagesContainer) {
                        messagesContainer.scrollTop = messagesContainer.scrollHeight;
                    }
                });
            },

            // Clear error
            clearError() {
                this.error = null;
            },

            // Get formatted time
            getFormattedTime(timestamp) {
                if (!timestamp) return '';
                const date = new Date(timestamp);
                return date.toLocaleTimeString('sv-SE', { hour: '2-digit', minute: '2-digit' });
            }
        }))
});
