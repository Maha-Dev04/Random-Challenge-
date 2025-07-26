// JavaScript for Random Challenge Generator Website

// Sample challenges array (can be loaded from XML via AJAX if needed)
let challenges = [
    {
        title: "Build a Responsive Portfolio Website",
        category: "Web Development",
        difficulty: "Intermediate",
        description: "Create a responsive portfolio website using HTML, CSS, and JavaScript.",
        requirements: "Must include a homepage, about section, and contact form.",
        tags: "html, css, javascript"
    },
    {
        title: "Design a Mobile To-Do App",
        category: "Mobile Apps",
        difficulty: "Beginner",
        description: "Design a simple to-do list app for mobile devices.",
        requirements: "Include add, edit, and delete task functionality.",
        tags: "mobile, design, todo"
    },
    {
        title: "Implement a Binary Search Tree",
        category: "Data Structures",
        difficulty: "Advanced",
        description: "Implement a binary search tree with insert, delete, and search operations.",
        requirements: "Use any programming language.",
        tags: "data structures, bst, algorithms"
    },
    {
        title: "Create a RESTful API",
        category: "API Development",
        difficulty: "Intermediate",
        description: "Build a RESTful API for a blog platform.",
        requirements: "Support CRUD operations for posts and comments.",
        tags: "api, rest, backend"
    }
];

// Current displayed challenge
let currentChallenge = null;

// DOM Elements
const generateBtn = document.getElementById('generate-btn');
const saveBtn = document.getElementById('save-btn');
const challengeDisplay = document.getElementById('challenge-display');

// Generate random challenge
function generateChallenge() {
    const randomIndex = Math.floor(Math.random() * challenges.length);
    currentChallenge = challenges[randomIndex];
    displayChallenge(currentChallenge);
    saveBtn.style.display = 'inline-block';
}

// Display challenge details
function displayChallenge(challenge) {
    challengeDisplay.innerHTML = `
        <h3>${challenge.title}</h3>
        <p><strong>Category:</strong> ${challenge.category}</p>
        <p><strong>Difficulty:</strong> ${challenge.difficulty}</p>
        <p>${challenge.description}</p>
        ${challenge.requirements ? `<p><strong>Requirements:</strong> ${challenge.requirements}</p>` : ''}
        ${challenge.tags ? `<p><strong>Tags:</strong> ${challenge.tags}</p>` : ''}
    `;
}

// Save current challenge to localStorage and XML via PHP
function saveChallenge() {
    if (!currentChallenge) return;

    // Save to localStorage
    let savedChallenges = JSON.parse(localStorage.getItem('savedChallenges') || '[]');
    savedChallenges.push({...currentChallenge, savedAt: new Date().toISOString()});
    localStorage.setItem('savedChallenges', JSON.stringify(savedChallenges));

    // Save to XML via PHP backend
    const formData = new FormData();
    formData.append('title', currentChallenge.title);
    formData.append('category', currentChallenge.category);
    formData.append('difficulty', currentChallenge.difficulty);
    formData.append('description', currentChallenge.description);
    formData.append('requirements', currentChallenge.requirements || '');
    formData.append('tags', currentChallenge.tags || '');

    fetch('save.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        alert('Error saving challenge.');
        console.error('Error:', error);
    });
}

// Event Listeners
generateBtn.addEventListener('click', generateChallenge);
saveBtn.addEventListener('click', saveChallenge);

// Hamburger menu toggle for mobile
const hamburger = document.querySelector('.hamburger');
const navMenu = document.querySelector('.nav-menu');

hamburger.addEventListener('click', () => {
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
});
