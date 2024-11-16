document.getElementById('examForm').addEventListener('submit', function(event) {
  event.preventDefault();

// Get form data
var title = document.getElementById('title').value;
var date = document.getElementById('date').value;
var courses = Array.from(document.querySelectorAll('input[name="courses[]"]:checked')).map(e => e.value); // This will be an array
var file = document.getElementById('file').files[0];
var yearID = document.querySelector('input[name="Years"]:checked').value; // This should match the 'name' attribute in your form

// Create form data
var formData = new FormData();
formData.append('title', title);
formData.append('date', date);
courses.forEach((courseID, i) => formData.append(`courses[${i}]`, courseID)); // Append each course
formData.append('file', file);
formData.append('yearID', yearID);
formData.append('action', 'add');

  // Send a POST request to the server
  fetch('examAction.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    // Log the response from the server
    console.log(data);

    // Display the exams in the form
    var examList = document.getElementById('examList');
    examList.innerHTML = '';
    data.forEach(function(exam) {
      var examElement = document.createElement('div');
      examElement.innerHTML = 'Exam ID: ' + exam.ExamID + ' - Title: ' + exam.title + ' - Date: ' + exam.date + ' <button class="deleteButton" data-id="' + exam.ExamID + '">Delete</button>';
      examList.appendChild(examElement);
    });

    // Add event listeners to the delete buttons
    document.querySelectorAll('.deleteButton').forEach(function(button) {
      button.addEventListener('click', function() {
        var examID = this.getAttribute('data-id');

        // Create form data
        var formData = new FormData();
        formData.append('examID', examID);
        formData.append('action', 'delete');

        // Send a POST request to the server
        fetch('examAction.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          // Log the response from the server
          console.log(data);

          // Optionally, you can refresh the exam list here
        })
        .catch((error) => {
          console.error('Error:', error);
        });
      });
    });
  })
  .catch((error) => {
    console.error('Error:', error);
  });
});
