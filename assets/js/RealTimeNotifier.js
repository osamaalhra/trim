// نظام إشعارات WebSocket
class RealTimeNotifier {
    constructor() {
        this.socket = null;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
    }
    
    connect() {
        this.socket = new WebSocket('wss://yourdomain.com/ws');
        
        this.socket.onopen = () => {
            console.log('Connected to WebSocket server');
            this.reconnectAttempts = 0;
        };
        
        this.socket.onmessage = (event) => {
            const data = JSON.parse(event.data);
            this.handleNotification(data);
        };
        
        this.socket.onclose = () => {
            console.log('WebSocket disconnected');
            this.attemptReconnect();
        };
    }
    
    handleNotification(data) {
        switch(data.type) {
            case 'task_completed':
                this.showSuccessNotification(`تم إكمال المهمة: ${data.taskTitle}`);
                break;
            case 'target_achieved':
                this.showAchievementNotification(`مبروك! لقد حققت هدفك اليومي`);
                break;
            case 'time_warning':
                this.showWarningNotification(`تحذير: ${data.message}`);
                break;
        }
    }
    
    showSuccessNotification(message) {
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('TRMS - إشعار', {
                body: message,
                icon: '/assets/icons/success.png'
            });
        }
    }
}