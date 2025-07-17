<x-app-layout>
    <div class="task-management-container">
        <!-- Daily Tasks Section -->
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title">Your Daily Tasks</h1>
        </div>

        <div class="task-items-wrapper">
            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Morning workout</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">08:00</div>
            </a>

            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Preparing the breakfast</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">07:00</div>
            </a>

            <a href="#" class="task-item-card pending-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Checking Emails</div>
                <div class="task-status-text">Task undone</div>
                <div class="task-time-display">08:00</div>
            </a>
        </div>

        <div class="section-divider"></div>

        <!-- Weekly Tasks Section -->
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title">Weekly Tasks</h1>
        </div>

        <div class="task-items-wrapper">
            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Morning workout</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">08:00</div>
            </a>

            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Preparing the breakfast</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">07:00</div>
            </a>

            <a href="#" class="task-item-card pending-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Checking Emails</div>
                <div class="task-status-text">Task undone</div>
                <div class="task-time-display">08:00</div>
            </a>
        </div>

        <div class="section-divider"></div>

        <!-- Monthly Tasks Section -->
        <div class="daily-tasks-header">
            <a class="back-arrow" href="/dashboard" title="Back">←</a>
            <h1 class="daily-tasks-title">Monthly Tasks</h1>
        </div>

        <div class="task-items-wrapper">
            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Morning workout</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">08:00</div>
            </a>

            <a href="#" class="task-item-card completed-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Preparing the breakfast</div>
                <div class="task-status-text">Task completed</div>
                <div class="task-time-display">07:00</div>
            </a>

            <a href="#" class="task-item-card pending-task">
                <div class="completion-indicator"></div>
                <div class="task-name-primary">Checking Emails</div>
                <div class="task-status-text">Task undone</div>
                <div class="task-time-display">08:00</div>
            </a>
        </div>
    </div>

    <script>
        // Add smooth hover effects and click animations
        document.addEventListener('DOMContentLoaded', function() {
            const taskCards = document.querySelectorAll('.task-item-card, .goal-item-card');
            
            taskCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
                
                card.addEventListener('mousedown', function() {
                    this.style.transform = 'translateY(-2px) scale(0.98)';
                });
                
                card.addEventListener('mouseup', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Add ripple effect
                    const ripple = document.createElement('div');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = (e.clientX - this.getBoundingClientRect().left) + 'px';
                    ripple.style.top = (e.clientY - this.getBoundingClientRect().top) + 'px';
                    ripple.style.width = ripple.style.height = '20px';
                    ripple.style.marginLeft = ripple.style.marginTop = '-10px';
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
        });
        
        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</x-app-layout>