$(document).ready(function() {
  fetchCoursesAndDepartments();
  fetchSemesters();

  $("#semesterForm").submit(function(event) {
      event.preventDefault();
      addSemester();
  });
});

function fetchCoursesAndDepartments() {
  $.ajax({
      url: 'fetch_courses_departments.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
          let courseOptions = '';
          let departmentOptions = '';

          data.courses.forEach(course => {
              courseOptions += `<option value="${course.CourseID}">${course.title}</option>`;
          });

          data.departments.forEach(department => {
              departmentOptions += `<option value="${department.dept_id}">${department.dept_name}</option>`;
          });

          $("#course").html(courseOptions);
          $("#department").html(departmentOptions);
      }
  });
}

function fetchSemesters() {
  $.ajax({
      url: 'fetch_semesters.php',
      type: 'GET',
      dataType: 'json',
      success: function(data) {
          let rows = '';

          data.forEach(semester => {
              rows += `
                  <tr>
                      <td>${semester.SemesterID}</td>
                      <td>${semester.courseTitle}</td>
                      <td>${semester.Startdate}</td>
                      <td>${semester.Enddate}</td>
                      <td>${semester.departmentName}</td>
                      <td>
                          <button class="btn btn-warning btn-sm" onclick="editSemester(${semester.SemesterID})">Edit</button>
                          <button class="btn btn-danger btn-sm" onclick="deleteSemester(${semester.SemesterID})">Delete</button>
                      </td>
                  </tr>
              `;
          });

          $("#semestersTable tbody").html(rows);
      }
  });
}

function addSemester() {
  const formData = {
      course: $("#course").val(),
      startdate: $("#startdate").val(),
      enddate: $("#enddate").val(),
      department: $("#department").val()
  };

  $.ajax({
      url: 'add_semester.php',
      type: 'POST',
      data: formData,
      success: function(response) {
          alert(response);
          fetchSemesters();
          $("#semesterForm")[0].reset();
      }
  });
}

function editSemester(id) {
  // Add code to handle edit
}

function deleteSemester(id) {
  $.ajax({
      url: 'delete_semester.php',
      type: 'POST',
      data: { SemesterID: id },
      success: function(response) {
          alert(response);
          fetchSemesters();
      }
  });
}
