import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


// Attach jQuery-based UI & AJAX helpers if jQuery is present
(function(){
	if (typeof window === 'undefined') return;
	if (typeof window.jQuery === 'undefined' && typeof window.$ === 'undefined') {
		console.warn('jQuery not found: skipping bootstrap jQuery helpers.');
		return;
	}

	var $ = window.jQuery || window.$;

	$(document).ready(function() {

		function appUrl(path) {
			var base = $('meta[name="app-url"]').attr('content') || '';
			base = base.replace(/\/+$/, '');
			if (!path) return base;
			if (path.charAt(0) !== '/') path = '/' + path;
			return base + path;
		}

		// CSRF token for all AJAX requests
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		// ---------- Demo UI interactions ----------
		$('#demoButton').click(function() {
			$('#demoParagraph').slideDown();
		});

		$('#demoHover').hover(
			function() {
				$(this).css('background-color', 'red');
				$('#demoParagraph').slideUp();
			},
			function() {
				$(this).css('background-color', '');
				$('#demoParagraph').slideDown();
			}
		);

		$('#demoForm').submit(function(event) {
			event.preventDefault();
			alert('Form was submitted!');
		});

		// ---------- Auto-refresh student list (every 5 seconds) ----------
		function autoReloadStudents() {
			var container = $('#studentsList');
			if (!container.length) return;

			var url = container.data('url') || appUrl('/student/list');

			$.get(url)
				.done(function(data) {
					container.html(data);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.warn('Auto-refresh students failed:', textStatus, errorThrown);
					var msg = 'Failed to load students (' + (jqXHR.status || 'no status') + '). URL: ' + url;
					container.html('<div class="alert alert-warning mb-0">' + msg + '</div>');
				});
		}
		if ($('#studentsList').length) {
			autoReloadStudents();
			setInterval(autoReloadStudents, 5000);
		}

		// ---------- Auto-refresh teacher list (every 5 seconds) ----------
		function autoReloadTeachers() {
			var container = $('#teachersList');
			if (!container.length) return;

			var url = container.data('url') || appUrl('/teacher/list');

			$.get(url)
				.done(function(data) {
					container.html(data);
				})
				.fail(function(jqXHR, textStatus, errorThrown) {
					console.warn('Auto-refresh teachers failed:', textStatus, errorThrown);
					var msg = 'Failed to load teachers (' + (jqXHR.status || 'no status') + '). URL: ' + url;
					container.html('<div class="alert alert-warning mb-0">' + msg + '</div>');
				});
		}
		if ($('#teachersList').length) {
			autoReloadTeachers();
			setInterval(autoReloadTeachers, 5000);
		}

		// ---------- Pagination (AJAX) ----------
		$(document).on('click', '#studentsList .pagination a, #teachersList .pagination a', function(e) {
			e.preventDefault();

			var url = $(this).attr('href');
			if (!url) return;

			var studentsContainer = $('#studentsList');
			var teachersContainer = $('#teachersList');
			var container = $(this).closest('#teachersList').length ? teachersContainer : studentsContainer;

			// Persist current page so auto-refresh doesn't jump back to page 1
			container.data('url', url);

			$.get(url)
				.done(function(data) {
					container.html(data);
				})
				.fail(function(jqXHR) {
					var msg = 'Failed to load page (' + (jqXHR.status || 'no status') + '). URL: ' + url;
					container.html('<div class="alert alert-warning mb-0">' + msg + '</div>');
				});
		});

		// ---------- Create Student ----------
		$(document).on('click', '#savedStudent', function(e) {
			e.preventDefault();

			var url = $(this).data('url') || appUrl('/student');

			var fname = $('#firstName').val();
			var mname = $('#middleName').val();
			var lname = $('#lastName').val();
			var email = $('#email').val();
			var degree = $('#degree').val();
			var contactNo = $('#contactNo').val();
			var username = $('#username').val();
			var password = $('#password').val();

			$.ajax({
				url: url,
				type: 'POST',
				data: {
					first_name: fname,
					middle_name: mname,
					last_name: lname,
					email: email,
					degree_id: degree,
					contact_no: contactNo,
					username: username,
					password: password
				},
				success: function(response) {
					alert('Student created successfully!');
					window.location.href = appUrl('/manageStudents');
				},
				error: function(xhr) {
					console.debug('AJAX error (student):', xhr.status, xhr.responseText);
					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						alert(msgs.join('\n'));
					} else {
						console.error('Student creation failed', xhr);
						var errorMsg = 'Failed to create student.';
						if (xhr.status === 500) {
							errorMsg += '\nServer error (500). Check Laravel log.';
						} else if (xhr.status === 404) {
							errorMsg += '\nRoute not found (404).';
						} else if (xhr.status === 419) {
							errorMsg += '\nCSRF token mismatch (419). Refresh page and try again.';
						} else if (xhr.status === 0) {
							errorMsg += '\nNetwork error or CORS issue. Check console.';
						}
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg += '\nMessage: ' + xhr.responseJSON.message;
						} else if (xhr.responseText) {
							errorMsg += '\nResponse (first 200 chars): ' + xhr.responseText.substring(0, 200);
						}
						alert(errorMsg);
					}
				}
			});
		});

		// ---------- Create Teacher (enhanced error alert) ----------
		$(document).on('click', '#savedTeacher', function(e) {
			e.preventDefault();

			var url = $(this).data('url') || appUrl('/teacher');

			var fname = $('#firstName').val();
			var mname = $('#middleName').val();
			var lname = $('#lastName').val();
			var email = $('#email').val();
			var contactNo = $('#contactNo').val();
			var username = $('#username').val();
			var password = $('#password').val();

			$.ajax({
				url: url,
				type: 'POST',
				data: {
					first_name: fname,
					middle_name: mname,
					last_name: lname,
					email: email,
					contact_no: contactNo,
					username: username,
					password: password
				},
				success: function(response) {
					alert('Teacher created successfully!');
					window.location.href = appUrl('/manageStudents');
				},
				error: function(xhr) {
					console.debug('AJAX error (teacher):', xhr.status, xhr.responseText);
					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						alert(msgs.join('\n'));
					} else {
						var errorMsg = 'Failed to create teacher.';
						if (xhr.status === 500) {
							errorMsg += '\nServer error (500). Check Laravel log.';
						} else if (xhr.status === 404) {
							errorMsg += '\nRoute not found (404). Ensure POST /teacher exists.';
						} else if (xhr.status === 419) {
							errorMsg += '\nCSRF token mismatch (419). Refresh page and try again.';
						} else if (xhr.status === 0) {
							errorMsg += '\nNetwork error or CORS issue. Check your connection.';
						}
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg += '\nMessage: ' + xhr.responseJSON.message;
						} else if (xhr.responseText) {
							errorMsg += '\nResponse (first 200 chars): ' + xhr.responseText.substring(0, 200);
						}
						alert(errorMsg);
					}
				}
			});
		});

		// ---------- Update Teacher (enhanced error alert) ----------
		$(document).on('click', '#updateTeacher', function() {
			var form = $('#editTeacherForm');
			if (!form.length) {
				console.warn('Update teacher: edit form not found');
				return;
			}

			var data = {
				first_name: form.find('#firstName').val(),
				middle_name: form.find('#middleName').val(),
				last_name: form.find('#lastName').val(),
				email: form.find('#email').val(),
				contact_no: form.find('#contactNo').val()
			};

			$.ajax({
				url: form.attr('action'),
				type: 'PUT',
				data: data,
				success: function(response) {
					alert('Teacher updated successfully!');
					window.location.href = appUrl('/manageStudents');
				},
				error: function(xhr) {
					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						alert(msgs.join('\n'));
					} else {
						var errorMsg = 'Failed to update teacher.';
						if (xhr.status === 500) errorMsg += '\nServer error (500).';
						else if (xhr.status === 404) errorMsg += '\nUpdate route not found.';
						else if (xhr.status === 419) errorMsg += '\nCSRF token mismatch.';
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg += '\nMessage: ' + xhr.responseJSON.message;
						}
						alert(errorMsg);
					}
				}
			});
		});

		// ---------- Update Student (AJAX) ----------
		$(document).on('submit', '#editStudentForm', function(e) {
			e.preventDefault();
			var form = $(this);
			var url = form.attr('action');

			var data = {
				_method: 'PUT',
				first_name: form.find('#firstName').val(),
				middle_name: form.find('#middleName').val(),
				last_name: form.find('#lastName').val(),
				email: form.find('#email').val(),
				contact_no: form.find('#contactNo').val(),
				degree_id: form.find('#degree').val()
			};

			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function() {
					alert('Student updated successfully!');
					window.location.href = appUrl('/manageStudents');
				},
				error: function(xhr) {
					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						alert(msgs.join('\n'));
					} else {
						var errorMsg = 'Failed to update student.';
						if (xhr.status === 500) errorMsg += '\nServer error (500).';
						else if (xhr.status === 404) errorMsg += '\nUpdate route not found.';
						else if (xhr.status === 419) errorMsg += '\nCSRF token mismatch.';
						if (xhr.responseJSON && xhr.responseJSON.message) {
							errorMsg += '\nMessage: ' + xhr.responseJSON.message;
						}
						alert(errorMsg);
					}
				}
			});
		});

		// ---------- Create Degree (AJAX) ----------
		$(document).on('submit', '#addDegreeForm', function(e) {
			e.preventDefault();
			var form = $(this);

			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				data: form.serialize(),
				success: function() {
					alert('Degree created successfully!');
					window.location.href = appUrl('/degree');
				},
				error: function(xhr) {
					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						alert(msgs.join('\n'));
					} else {
						var errorMsg = 'Failed to create degree.';
						if (xhr.status === 500) errorMsg += '\nServer error (500).';
						else if (xhr.status === 404) errorMsg += '\nStore route not found.';
						else if (xhr.status === 419) errorMsg += '\nCSRF token mismatch.';
						alert(errorMsg);
					}
				}
			});
		});

		// ---------- Delete Teacher (enhanced error alert) ----------
		$(document).on('click', '.btn-delete-teacher', function(e) {
			e.preventDefault();
			if (!confirm('Delete this teacher?')) return;
			var btn = $(this);
			var url = btn.data('action');

			$.ajax({
				url: url,
				type: 'DELETE',
				success: function(response) {
					var tr = btn.closest('tr');
					var id = tr.data('id') || null;
					tr.remove();
					try {
						var payload = { ts: Date.now(), data: { action: 'deleted', model: 'teacher', record: { id: id } } };
						localStorage.setItem('jmm_ajax_event', JSON.stringify(payload));
						console.debug('[jmm] broadcast delete teacher', payload);
					} catch (ex) { console.warn('[jmm] broadcast failed', ex); }
					alert('Teacher deleted successfully!');
				},
				error: function(xhr) {
					var errorMsg = 'Failed to delete teacher.';
					if (xhr.status === 500) errorMsg += '\nServer error.';
					else if (xhr.status === 404) errorMsg += '\nDelete route not found.';
					alert(errorMsg);
				}
			});
		});

		// ---------- Delete Student (AJAX via modal form) ----------
		$(document).on('submit', '#deleteStudentForm', function(e) {
			e.preventDefault();
			var form = $(this);
			var url = form.attr('action');
			if (!url) {
				alert('Delete action is missing.');
				return;
			}

			$.ajax({
				url: url,
				type: 'POST',
				data: {
					_method: 'DELETE'
				},
				success: function() {
					var modalEl = document.getElementById('deleteStudentModal');
					if (modalEl && window.bootstrap) {
						var modal = window.bootstrap.Modal.getInstance(modalEl);
						if (modal) modal.hide();
					}
					// broadcast delete to other tabs
					try {
						var formAction = form.attr('action') || '';
						var id = (formAction.split('/').pop() || null);
						var payload = { ts: Date.now(), data: { action: 'deleted', model: 'student', record: { id: id } } };
						localStorage.setItem('jmm_ajax_event', JSON.stringify(payload));
						console.debug('[jmm] broadcast delete student', payload);
					} catch (ex) { console.warn('[jmm] broadcast failed', ex); }
					autoReloadStudents();
					alert('Student deleted successfully!');
				},
				error: function(xhr) {
					var errorMsg = 'Failed to delete student.';
					if (xhr.status === 500) errorMsg += '\nServer error.';
					else if (xhr.status === 404) errorMsg += '\nDelete route not found.';
					else if (xhr.status === 419) errorMsg += '\nCSRF token mismatch.';
					alert(errorMsg);
				}
			});
		});

		// ---------- Login (AJAX) ----------
		$(document).on('click', '#loginBtn', function(e) {
			e.preventDefault();
			var $btn = $(this);
			var $alert = $('#loginAlert');
			var username = $('#username').val();
			var password = $('#password').val();

			// Basic client-side validation
			if (!username || !password) {
				if ($alert.length) {
					$alert.removeClass('d-none').text('Please enter both username and password.');
				} else {
					alert('Please enter both username and password.');
				}
				return;
			}

			// Disable button to prevent double submission
			$btn.prop('disabled', true).text('Logging in...');
			if ($alert.length) $alert.addClass('d-none').text('');

			$.ajax({
				url: appUrl('/'),   // Login POST endpoint
				type: 'POST',
				data: {
					username: username,
					password: password
				},
				success: function(response) {
					// Redirect based on user role
					if (response.role === 'student') {
						window.location.href = appUrl('/studentDashboard');
					} else if (response.role === 'teacher') {
						window.location.href = appUrl('/manageStudents');
					} else {
						var redirectUrl = response.redirect || appUrl('/manageStudents');
						window.location.href = redirectUrl;
					}
				},
				error: function(xhr) {
					$btn.prop('disabled', false).text('Login');
					var message = 'Login failed. Please try again.';

					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						message = msgs.join('\n');
					} else if (xhr.status === 401) {
						message = xhr.responseJSON?.message || 'Invalid username or password.';
					} else if (xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}

					if ($alert.length) {
						$alert.removeClass('d-none').text(message);
					} else {
						alert(message);
					}
				}
			});
		});

		// ---------- Update Student (AJAX) – no <form> version ----------
		$(document).on('click', '#updateStudentBtn', function(e) {
			e.preventDefault();
			var $btn = $(this);
			var $alert = $('#editStudentAlert');
			var url = $btn.data('url') || window.location.href;

			var data = {
				_token: $('#csrf_token').val(),
				_method: 'PUT',
				first_name: $('#firstName').val(),
				middle_name: $('#middleName').val(),
				last_name: $('#lastName').val(),
				email: $('#email').val(),
				contact_no: $('#contactNo').val(),
				degree_id: $('#degree').val()
			};

			if (!data.first_name || !data.last_name) {
				if ($alert.length) {
					$alert.removeClass('d-none').text('First Name and Last Name are required.');
				} else {
					alert('First Name and Last Name are required.');
				}
				return;
			}

			$btn.prop('disabled', true).text('Updating...');
			if ($alert.length) $alert.addClass('d-none').text('');

			$.ajax({
				url: url,
				type: 'POST',
				data: data,
				success: function(response) {
					alert('Student updated successfully!');
					window.location.href = appUrl('/manageStudents');
				},
				error: function(xhr) {
					$btn.prop('disabled', false).text('Update');
					var message = 'Update failed. Please try again.';

					if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
						var errors = xhr.responseJSON.errors;
						var msgs = [];
						for (var k in errors) {
							if (errors.hasOwnProperty(k)) msgs.push(errors[k].join('\n'));
						}
						message = msgs.join('\n');
					} else if (xhr.responseJSON && xhr.responseJSON.message) {
						message = xhr.responseJSON.message;
					}

					if ($alert.length) {
						$alert.removeClass('d-none').text(message);
					} else {
						alert(message);
					}
				}
			});
		});

	});

})();
