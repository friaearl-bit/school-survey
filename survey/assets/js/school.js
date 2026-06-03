var data = [
    ["Effective Communication","Alexandria Reyes"],
    ["Effective Communication","Rade Archiel Tejano"],
    ["Life and Career Skills", "Maverick Evander"],
    ["Life and Career Skills","Vivienne Cruz"],
    ["General Mathematics","Ronan Slade Veniel"],
    ["General Mathematics","Aldrin Denver Ferrin"],
    ["General Science","Scarlet Salazar"],
    ["General Science","Bianca Montevend"],
    ["Pag-aaral ng Kasaysayan at Lipunang Pilipino","Nabia Gatchalian"],
    ["Pag-aaral ng Kasaysayan at Lipunang Pilipino","Nieve Nicolas"],
];

// Dropdown population
const selectSubject = document.getElementById("subject");
const selectTeacher = document.getElementById("teacher");

if(selectSubject) {
    selectSubject.addEventListener("change", function() {
        selectTeacher.innerHTML = '<option value="" disabled selected>Select a teacher</option>';
        const selected = this.value;
        const filteredTeachers = data.filter(row => row[0] === selected);
        filteredTeachers.forEach(row => {
            let option = new Option(row[1], row[1]);
            selectTeacher.add(option);
        });
        evaluateLiveCards(); // re-trigger validation when changed
    });
}

// Anonymous Checkbox Toggle
const anonCheckbox = document.getElementById('anonymous');
function isAnonymouss() {
    anonCheckbox.addEventListener('change', function(){
        const surnameField = document.getElementById('surname');
        const gNameField = document.getElementById('gName');
        const mNameField = document.getElementById('mName');
        const emailField = document.getElementById('email');
        const studentNumberField = document.getElementById('studentNumber');
        
        if(this.checked){
            surnameField.disabled = true; gNameField.disabled = true; mNameField.disabled = true;
            emailField.disabled = true; studentNumberField.disabled = true;
            surnameField.value = ''; gNameField.value = ''; mNameField.value = '';
            emailField.value = ''; studentNumberField.value = '';
            surnameField.placeholder = 'Anonymous'; gNameField.placeholder = 'Anonymous'; mNameField.placeholder = 'Anonymous';
            emailField.placeholder = 'Anonymous'; studentNumberField.placeholder = 'Anonymous';
        } else {
            surnameField.disabled = false; gNameField.disabled = false; mNameField.disabled = false;
            emailField.disabled = false; studentNumberField.disabled = false;
            surnameField.placeholder = 'e.g. Dela Cruz'; gNameField.placeholder = 'e.g. Juan'; mNameField.placeholder = 'e.g. Soria';
            emailField.placeholder = 'email@school.edu'; studentNumberField.placeholder = '2024-00000-MN-0';
        }
        evaluateLiveCards();
    });
}
if(anonCheckbox) isAnonymouss();

// Live Tile Validation (Turns cards Green)
function evaluateLiveCards() {
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const requiredInputs = card.querySelectorAll('[required]');
        if (requiredInputs.length === 0) return; 
        
        let isComplete = true;
        const radioGroups = {};
        
        requiredInputs.forEach(req => {
            // Ignore disabled fields (like names when anonymous is checked)
            if (req.disabled) return; 

            if (req.type === 'radio') {
                if (!radioGroups[req.name]) radioGroups[req.name] = false;
                if (req.checked) radioGroups[req.name] = true;
            } else {
                if (!req.value.trim()) isComplete = false;
            }
        });
        
        // Check radio groups
        for (const group in radioGroups) {
            if (!radioGroups[group]) isComplete = false;
        }
        
        if (isComplete) {
            card.classList.add('card-completed');
        } else {
            card.classList.remove('card-completed');
        }
    });
}

// Setup Event Listeners for Live Validation
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('input', evaluateLiveCards);
        input.addEventListener('change', evaluateLiveCards);
    });
    evaluateLiveCards(); // Run once on load
});

// HomePage Restriction Check
function validateHomePage() {
    let isValid = true;
    let errors = [];

    const isAnon = document.getElementById('anonymous').checked;
    
    if (!isAnon) {
        if(!document.getElementById('surname').value.trim()) errors.push("Surname");
        if(!document.getElementById('gName').value.trim()) errors.push("Given Name");
    }
    
    if(!document.getElementById('email').value.trim()) errors.push("Email");
    if(!document.getElementById('dtoday').value) errors.push("Date");
    if(!document.getElementById('studentNumber').value.trim()) errors.push("Student Number");
    if(!document.getElementById('section').value) errors.push("Section");

    if (errors.length > 0) {
        alert("Please complete the following required fields:\n- " + errors.join("\n- "));
    } else {
        window.location.href = 'ClassroomSurvey.html';
    }
}

// Survey Page Restriction Check
function validateSurveyPage() {
    let errors = [];

    if(!document.getElementById('subject').value) errors.push("Subject");
    if(!document.getElementById('teacher').value) errors.push("Teacher");

    // Get all radio groups required in the survey
    const requiredRadios = Array.from(document.querySelectorAll('input[type="radio"][required]'));
    const names = [...new Set(requiredRadios.map(r => r.name))];
    
    let missingRadios = 0;
    names.forEach(name => {
        if (!document.querySelector(`input[name="${name}"]:checked`)) {
            missingRadios++;
        }
    });

    if (missingRadios > 0) {
        errors.push(`You missed ${missingRadios} rating question(s). Please review the stars.`);
    }

    if (errors.length > 0) {
        alert("Please complete the following before submitting:\n- " + errors.join("\n- "));
    } else {
        window.location.href = 'thank_you.php';
    }
}
