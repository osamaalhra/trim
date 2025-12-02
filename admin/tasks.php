<?php
// ... (الكود السابق يبقى كما هو مع التعديلات التالية)

// في قسم معالجة إضافة مهمة جديدة
if(isset($_POST['add_task'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $estimated_time = $_POST['estimated_time'];
    $difficulty = $_POST['difficulty'];
    
    $stmt = $pdo->prepare("INSERT INTO tasks (title, description, estimated_time, difficulty) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $estimated_time, $difficulty]);
    
    header('Location: tasks.php?success=1');
    exit();
}

// ... (الباقي يبقى كما هو)

// في واجهة المستخدم، استبدال حقل النقاط بحقل الوقت
?>
<!-- في الـ Modal -->
<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="estimated_time" class="form-label">الوقت المقدر (بالدقائق)</label>
            <input type="number" class="form-control" id="estimated_time" name="estimated_time" min="1" max="1440" required>
            <div class="form-text">الوقت المقدر لإنجاز المهمة بالدقائق (1-1440)</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="difficulty" class="form-label">مستوى الصعوبة</label>
            <select class="form-select" id="difficulty" name="difficulty" required>
                <option value="سهلة">سهلة</option>
                <option value="متوسطة">متوسطة</option>
                <option value="صعبة">صعبة</option>
            </select>
        </div>
    </div>
</div>

<script>
// تحديث الوقت المقدر بناءً على الصعوبة
document.getElementById('difficulty').addEventListener('change', function() {
    const timeInput = document.getElementById('estimated_time');
    const difficulty = this.value;
    
    switch(difficulty) {
        case 'سهلة':
            timeInput.value = 30;
            break;
        case 'متوسطة':
            timeInput.value = 120;
            break;
        case 'صعبة':
            timeInput.value = 240;
            break;
    }
});
</script>