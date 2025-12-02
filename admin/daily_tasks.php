<?php
// ... (الكود السابق مع التعديلات)

// حساب إجمالي الوقت
$total_time = 0;
foreach($assigned_tasks as $task) {
    $total_time += $task['estimated_time'];
}

// دالة تحويل الوقت
function formatTime($minutes) {
    $hours = floor($minutes / 60);
    $mins = $minutes % 60;
    return $hours > 0 ? "{$hours} ساعة و {$mins} دقيقة" : "{$mins} دقيقة";
}
?>

<!-- تحديث الواجهة -->
<div class="d-flex justify-content-between align-items-center">
    <div class="time-display">
        إجمالي الوقت المحدد: 
        <span id="totalTime">0</span> / <?php echo formatTime(DAILY_TIME_LIMIT); ?>
    </div>
    <button type="submit" name="assign_tasks" class="btn btn-success">
        <i class="fas fa-calendar-check"></i> حفظ التوزيع
    </button>
</div>

<script>
// حساب الوقت الإجمالي
function calculateTotalTime() {
    let total = 0;
    document.querySelectorAll('.task-checkbox:checked').forEach(checkbox => {
        total += parseInt(checkbox.getAttribute('data-time'));
    });
    
    document.getElementById('totalTime').textContent = formatTime(total);
    
    const progress = (total / <?php echo DAILY_TIME_LIMIT; ?>) * 100;
    const progressBar = document.getElementById('timeProgress');
    progressBar.style.width = progress + '%';
    
    if(total > <?php echo DAILY_TIME_LIMIT; ?>) {
        progressBar.classList.remove('bg-success');
        progressBar.classList.add('bg-danger');
        document.querySelector('button[type="submit"]').disabled = true;
    } else {
        progressBar.classList.remove('bg-danger');
        progressBar.classList.add('bg-success');
        document.querySelector('button[type="submit"]').disabled = false;
    }
}

// دالة تحويل الوقت
function formatTime(minutes) {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return hours > 0 ? `${hours} ساعة و ${mins} دقيقة` : `${mins} دقيقة`;
}

// تحديث عند تغيير الاختيارات
document.querySelectorAll('.task-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', calculateTotalTime);
});
</script>