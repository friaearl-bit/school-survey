<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


$adminData   = $_SESSION['admin_data'];
$studentData = [];
$sections    = [];


// Check if we're editing a student on the students page
if (($_GET['page'] ?? '') === 'students' && isset($_GET['action']) && $_GET['action'] === 'edit') {
    $studentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($studentId) {
        // Load sections for dropdown
        $stmt = $pdo->query("SELECT * FROM section");
        $sections = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Load student data
        $stmt = $pdo->prepare("SELECT student_id, student_number, surname, middle_name, given_name, email, s.section_id FROM student LEFT JOIN section s ON s.section_id = student.section_id WHERE student.student_id = ? ORDER BY student_id, surname");
        $stmt->execute([$studentId]);
        $studentData = $stmt->fetch(PDO::FETCH_ASSOC);

    }
}

?>

<!-- Admin Profile Modal -->
<div id="adminModal" class="modal" aria-hidden="true">
    <div class="card modal-card">
        <button id="adminModalClose" class="close" aria-label="Close">&times;</button>
        <h3 class="modal-card-title">Profile Information</h3><br>
        <form action="/survey/admin/update-admin.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="admin_id" value="<?= e($adminData['admin_id']) ?>">


            <?php if (!empty($_SESSION['flash_error'])): ?>
                <div class="card flash-card error"><p><?= e($_SESSION['flash_error']) ?></p></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_success_admin'])): ?>
                <div class="card flash-card success"><p><?= e($_SESSION['flash_success_admin']) ?></p></div>
                <?php unset($_SESSION['flash_success_admin']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_info_admin'])): ?>
                <div class="card flash-card info"><p><?= e($_SESSION['flash_info_admin']) ?></p></div>
                <?php unset($_SESSION['flash_info_admin']); ?>
            <?php endif; ?>


            <div class="profile-picture">
                <img width="240" height="240" alt="profile picture" src="/survey/admin/assets/images/placeholder-pic-businessman.jpg"></img>
                <button type="button" class="pic-edit-btn" aria-label="Edit">
                    <i data-lucide="edit" class="pic-edit-icon"></i>
                </button>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="fname" class="form-label">First Name</label>
                    <input class="form-input" type="text" id="fname" name="fname" value="<?= e($adminData['first_name'] ?? '') ?>" placeholder="Enter your first name" required>
                </div>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label for="lname" class="form-label">Last Name</label>
                    <input class="form-input" type="text" id="lname" name="lname" value="<?= e($adminData['last_name'] ?? '') ?>" placeholder="Enter your last name">
                </div>
            </div>

            <div class="footer-actions">
                <div class="button-container">
                    <button type="submit" class="btn-submit">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- STUDENT MODAL | ACTIONS: EDIT, DELETE, RESTORE -->

<div id="studentModal" class="modal" aria-hidden="true">
    <div class="card modal-card">
        <button id="studentModalClose" class="close" aria-label="Close">&times;</button>
        <h3 class="modal-card-title" id="modalTitle">Action</h3><br>
        <form id="studentActionForm" method="POST" action="/survey/admin/student-action.php">
            <input type="hidden" name="student_id" id="modalStudentId">
            <input type="hidden" name="action" id="modalAction">
            <input class="form-input" type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

            <?php if (!empty($_SESSION['errors'])): ?>
                <div class="card flash-card error"><ul><?php foreach($_SESSION['errors'] as $er): ?><li><p><?= e($er) ?></p></li><?php endforeach; ?></ul></div>
            <?php endif; ?>

            <?php if (!empty($_SESSION['flash_info_student'])): ?>
                <div class="card flash-card info"><p><?= e($_SESSION['flash_info_student']) ?></p></div>
                <?php unset($_SESSION['flash_info_student']); ?>
            <?php endif; ?>

                <!-- Dynamic content area -->
            <div id="modalContent">
                <!-- Populated by JavaScript based on action -->
            </div>

            <div class="footer-actions">
                <div class="button-container">
                    <button type="submit" class="btn-submit" id="modalSubmitBtn">Confirm</button>
                    <button type="button" class="btn-back" id="modalCloseBtn">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>




<div id="flashModal" class="modal" aria-hidden="true">
    <div class="card modal-card">
        <h3 class="modal-card-title" id="flashModalTitle"></h3><br>
        <button id="flashModalClose" class="close" aria-label="Close">&times;</button>

        <?php // debug(); ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
            <h3 class="modal-card-title">Error</h3><br>
            <div class="card flash-card error"><p><?= e($_SESSION['flash_error']) ?></p></div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <h3 class="modal-card-title">Success</h3><br>
            <div class="card flash-card success"><p><?= e($_SESSION['flash_success']) ?></p></div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_info'])): ?>
            <h3 class="modal-card-title">System Message</h3><br>
            <div class="card flash-card info"><p><?= e($_SESSION['flash_info']) ?></p></div>
            <?php unset($_SESSION['flash_info']); ?>
        <?php endif; ?>
        

        <div id="flashModalContent"></div>

        <div class="footer-actions">
            <div class="button-container">
                <button type="button" class="btn-submit flex-1" id="flashModalCloseBtn" tabindex="1">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($_SESSION['flash_error']) || !empty($_SESSION['flash_success']) || !empty($_SESSION['flash_info'])): ?>

<?php endif; ?>


<script>


// <!-- Pass student data to JavaScript -->
var studentData = <?php echo json_encode($studentData ?? []); ?>;
var sections    = <?php echo json_encode($sections); ?>;

document.addEventListener('DOMContentLoaded', function() {

    // Get URL Parameters
    const urlParams = new URLSearchParams(window.location.search);
    const page      = urlParams.get('page');
    const status    = urlParams.get('status');
    const action    = urlParams.get('action');
    const studentId = urlParams.get('id');
    const adminId   = urlParams.get('adminId');

    // Show or Hide Helpers
    const show = (m) => { if(!m) return; m.classList.add('show');    m.setAttribute('aria-hidden', 'false'); };
    const hide = (m) => { if(!m) return; m.classList.remove('show'); m.setAttribute('aria-hidden', 'true' ); };

    // Clear Parameters on close
    const clearParams = () => {
        const u = new URL(location);
        u.searchParams.delete('id');
        u.searchParams.delete('adminId');
        u.searchParams.delete('action');
        u.searchParams.delete('status');
        history.replaceState(null,'',u);
    };

    ////////////////////////////////////////////////////////////

    // Student Modal
    const modal              = document.getElementById('studentModal');
    const modalClose         = document.getElementById('studentModalClose');
    const modalTitle         = document.getElementById('modalTitle');
    const modalContent       = document.getElementById('modalContent');
    const modalAction        = document.getElementById('modalAction');
    const modalStudentId     = document.getElementById('modalStudentId');
    const modalSubmitBtn     = document.getElementById('modalSubmitBtn');
    const modalCloseBtn      = document.getElementById('modalCloseBtn');

    // Flash Modal
    const flashModal         = document.getElementById('flashModal');
    const flashModalClose    = document.getElementById('flashModalClose');
    const flashModalCloseBtn = document.getElementById('flashModalCloseBtn');
    const flashModalTitle    = document.getElementById('flashModalTitle');
    const flashModalContent  = document.getElementById('flashModalContent');

    // Admin Modal
    const adminModal         = document.getElementById('adminModal');
    const adminModalClose    = document.getElementById('adminModalClose');

    // Show Flash Modal Success/Failed
    if (status)            { show(flashModal); }
    if (action && adminId) { show(adminModal); }


    if ((page === 'sections' || page === 'responses') && action) {
        flashModalTitle.textContent = 'System Message';
        flashModalContent.innerHTML = `<p><strong>Feature not implemented yet.</strong></p>`;
        show(flashModal);
    }





    // Check if modal should be shown
    if ((page === 'students' || page === 'deleted') && action && studentId) {
    // if (action && studentId) {
        modalAction.value    = action;
        modalStudentId.value = studentId;


        // Customize modal based on action
        switch (action) {
            case 'edit':
                modalTitle.textContent = 'Edit Student';
                modalContent.innerHTML = `
                    <div class="form-row">
                        <div class="form-group">
                            <label for="surname">Surname</label>
                            <input class="form-input" type="text" id="surname" name="surname" tabindex="1"
                                   value="<?= e($studentData['surname'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="given_name">Given Name</label>
                            <input class="form-input" type="text" id="given_name" name="given_name" tabindex="1"
                                   value="<?= e($studentData['given_name'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input class="form-input" type="text" id="middle_name" name="middle_name" tabindex="1"
                                   value="<?= e($studentData['middle_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-input" type="email" id="email" name="email" tabindex="1"
                                   value="<?= e($studentData['email'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="student_number">Student Number</label>
                            <input class="form-input" type="text" id="student_number" name="student_number" tabindex="1"
                                   value="<?= e($studentData['student_number'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="section_id">Section</label>
                            <select class="form-input" id="section_id" name="section_id" tabindex="1" required>
                                <option value="" disabled>Select Section</option>
                                <?php foreach ($sections as $section): ?>
                                    <option value="<?= e($section['section_id']) ?>"
                                        ${studentData?.section_id == <?= $section['section_id'] ?> ? 'selected' : ''}>
                                        <?= e($section['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                `;
                modalSubmitBtn.textContent = 'Save Changes';
                break;

            case 'delete':
                modalTitle.textContent = 'Delete Student';
                modalContent.innerHTML = `
                    <p>Are you sure you want to <strong>delete</strong> student #${studentId}?</p>
                    <p>This will soft-delete the student and all their responses.</p>
                `;
                modalSubmitBtn.textContent = 'Delete';
                break;

            case 'restore':
                modalTitle.textContent = 'Restore Student';
                modalContent.innerHTML = `
                    <p>Are you sure you want to <strong>restore</strong> student #${studentId}?</p>
                    <p>This will restore the student and all their responses.</p>
                `;
                modalSubmitBtn.textContent = 'Restore';
                break;
        }
        // Show student modal after populating values; server-side rendering
        show(studentModal);
    }


    // Close handlers
    modalClose?.addEventListener('click'           , () => { hide(studentModal); clearParams(); });
    modalCloseBtn?.addEventListener('click'        , () => { hide(studentModal); clearParams(); });
    flashModalClose?.addEventListener('click'      , () => { hide(flashModal);   clearParams(); });
    flashModalCloseBtn?.addEventListener('click'   , () => { hide(flashModal);   clearParams(); });
    adminModalClose?.addEventListener('click'      , () => { hide(adminModal);   clearParams(); });


    // ESC closes modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') { hide(studentModal); hide(flashModal); hide(adminModal); clearParams(); }
    });
});

</script>


<?php
// Unset Flash Messages after being shown to 
unset($_SESSION['flash_error']);
unset($_SESSION['errors']);
unset($_SESSION['flash_success']);
?>