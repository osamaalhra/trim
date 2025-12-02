// خوارزمية تحسين اختيار المهام
class TaskOptimizer {
    constructor(strategy = 'balanced') {
        this.strategy = strategy;
    }
    
    optimize(tasks, availableTime, targetRevenue) {
        let optimizedTasks = [];
        let totalTime = 0;
        let totalRevenue = 0;
        
        // فرز المهام بناءً على الاستراتيجية
        const sortedTasks = tasks.sort((a, b) => {
            const scoreA = this.calculateScore(a);
            const scoreB = this.calculateScore(b);
            return scoreB - scoreA;
        });
        
        for (const task of sortedTasks) {
            if (totalTime + task.estimatedTime <= availableTime &&
                totalRevenue + task.price <= targetRevenue * 1.2) {
                
                optimizedTasks.push({
                    ...task,
                    scheduledStart: this.calculateStartTime(totalTime),
                    scheduledEnd: this.calculateStartTime(totalTime + task.estimatedTime)
                });
                
                totalTime += task.estimatedTime;
                totalRevenue += task.price;
            }
        }
        
        return {
            tasks: optimizedTasks,
            totalTime,
            totalRevenue,
            efficiency: totalRevenue / totalTime
        };
    }
    
    calculateScore(task) {
        switch(this.strategy) {
            case 'max_revenue':
                return task.price / task.estimatedTime;
            case 'min_time':
                return 1 / task.estimatedTime;
            case 'balanced':
            default:
                const revenuePerMinute = task.price / task.estimatedTime;
                const difficultyFactor = {
                    'low': 1.2,
                    'medium': 1.0,
                    'high': 0.8
                };
                return revenuePerMinute * difficultyFactor[task.difficulty];
        }
    }
    
    calculateStartTime(totalMinutes) {
        const startHour = 9; // 9:00 صباحاً
        const hours = Math.floor(totalMinutes / 60);
        const minutes = totalMinutes % 60;
        
        let hour = startHour + hours;
        const period = hour >= 12 ? 'م' : 'ص';
        hour = hour > 12 ? hour - 12 : hour;
        
        return `${hour.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')} ${period}`;
    }
}