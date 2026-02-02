/**
 * Signup Page Logic
 * Handles role switching and dynamic UI updates
 */

document.addEventListener('DOMContentLoaded', () => {
    initRoleSwitcher();
});

function initRoleSwitcher() {
    const roleBtns = document.querySelectorAll('.role-btn');
    const targetRoleInput = document.getElementById('target_role');
    const candidateFields = document.getElementById('candidate_fields');
    const sidePanel = document.querySelector('.side-panel');

    // Gradients
    const GRADIENT_VOTER = 'linear-gradient(135deg, #C8102E 0%, #003893 100%)';
    const GRADIENT_CANDIDATE = 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)';

    roleBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            // Determine selected role based on class
            const isAbstaining = this.classList.contains('role-voter');
            const role = isAbstaining ? 'voter' : 'candidate';

            // 1. Update Hidden Input
            if (targetRoleInput) targetRoleInput.value = role;

            // 2. Update Toggle Buttons Visual State
            roleBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // 3. Show/Hide Candidate Fields
            if (candidateFields) {
                candidateFields.style.display = (role === 'candidate') ? 'block' : 'none';
            }

            // 4. Update Sidebar Content
            document.querySelectorAll('.side-info').forEach(info => {
                info.classList.remove('active');
            });
            const activeSideInfo = document.getElementById('side_content_' + role);
            if (activeSideInfo) {
                activeSideInfo.classList.add('active');
            }

            // 5. Update Sidebar Gradient
            if (sidePanel) {
                sidePanel.style.background = (role === 'candidate') ? GRADIENT_CANDIDATE : GRADIENT_VOTER;
            }
        });
    });
}
