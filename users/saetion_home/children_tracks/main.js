
  
 // دالة لحساب نسبة التشابه بين النصوص
function calculateSimilarity(a, b) {
    const longer = a.length > b.length ? a : b;
    const shorter = a.length > b.length ? b : a;
  
    const longerLength = longer.length;
    if (longerLength === 0) {
      return 1.0; // النصوص متطابقة
    }
  
    const editDistance = getEditDistance(longer, shorter);
    return (longerLength - editDistance) / longerLength;
  }
  
  // دالة حساب المسافة (Edit Distance)
  function getEditDistance(a, b) {
    const matrix = [];
    for (let i = 0; i <= b.length; i++) {
      matrix[i] = [i];
    }
    for (let j = 0; j <= a.length; j++) {
      matrix[0][j] = j;
    }
  
    for (let i = 1; i <= b.length; i++) {
      for (let j = 1; j <= a.length; j++) {
        if (b.charAt(i - 1) === a.charAt(j - 1)) {
          matrix[i][j] = matrix[i - 1][j - 1];
        } else {
          matrix[i][j] = Math.min(
            matrix[i - 1][j - 1] + 1, // الاستبدال
            Math.min(
              matrix[i][j - 1] + 1,   // الإضافة
              matrix[i - 1][j] + 1    // الحذف
            )
          );
        }
      }
    }
  
    return matrix[b.length][a.length];
  }
  
  // البحث المرن عن الكورسات
  function searchCourses() {
    const input = document.getElementById('search-input').value.trim().toLowerCase();
    const courses = document.querySelectorAll('.course');
    const noResults = document.getElementById('no-results');
    let found = false;
  
    courses.forEach(course => {
      const courseTitle = course.querySelector('h2').textContent.trim().toLowerCase();
      const similarity = calculateSimilarity(courseTitle, input);
  
      // اعتبر أن الكورس مطابق إذا كانت النسبة > 0.6
      if (similarity > 0.6 || courseTitle.includes(input)) {
        course.style.display = 'block';
        found = true;
      } else {
        course.style.display = 'none';
      }
    });
  
    // عرض أو إخفاء رسالة "لم يتم العثور على نتائج"
    if (found) {
      noResults.classList.add('hidden');
    } else {
      noResults.classList.remove('hidden');
    }
  }
  
  
  
 