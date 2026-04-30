// public/js/minigame.js
// Raining Food minigame script

const foodItems = ['🍎','🍌','🍐','🍊','🍋','🍉','🍇','🍓','🥕','🥦','🥒','🍒','🍍','🍑','🍆','🌽','🥬','🍅','🥝','🍈'];
const junkItems = ['🧻','💊','🪙','🧲','🧦','🪥','🧴','🧢'];
let objects = [], player = {x:200, y:570, w:60, h:20}, score = 0, speed = 2, running = false, gameInterval;

function startGame() {
    objects = [];
    score = 0;
    speed = 2;
    running = true;
    player.x = 200;
    document.getElementById('gameOver').innerText = '';
    document.getElementById('score').innerText = 'Punkti: 0';
    if(gameInterval) clearInterval(gameInterval);
    gameInterval = setInterval(gameLoop, 20);
}

function gameLoop() {
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    ctx.clearRect(0,0,400,600);
    // Draw player
    ctx.fillStyle = '#4299e1';
    ctx.fillRect(player.x, player.y, player.w, player.h);
    // Move objects
    if(Math.random() < 0.04) {
        let isFood = Math.random() < 0.7;
        objects.push({
            x:Math.random()*340+10,
            y:-30,
            type:isFood?'food':'junk',
            emoji:isFood?foodItems[Math.floor(Math.random()*foodItems.length)]:junkItems[Math.floor(Math.random()*junkItems.length)]
        });
    }
    for(let i=0;i<objects.length;i++) {
        let o = objects[i];
        o.y += speed;
        ctx.font = '32px serif';
        ctx.fillText(o.emoji, o.x, o.y);
        // Collision
        if(o.y+28 > player.y && o.x > player.x-20 && o.x < player.x+player.w+10) {
            if(o.type==='food') {
                score++;
                document.getElementById('score').innerText = 'Punkti: '+score;
                objects.splice(i,1); i--;
                if(score%10===0) speed+=0.5;
            } else {
                running = false;
                document.getElementById('gameOver').innerText = 'Spēle beigusies! Gala rezultāts: '+score;
                clearInterval(gameInterval);
                return;
            }
        }
    }
    // Remove objects out of bounds
    objects = objects.filter(o => o.y < 620);
}

// Kontrole ar bultām
window.addEventListener('keydown',function(e){
    if(!running) return;
    if(e.key==='ArrowLeft') player.x=Math.max(0,player.x-30);
    if(e.key==='ArrowRight') player.x=Math.min(340,player.x+30);
});

// Automātiski sāk spēli, kad lapa ielādējas
window.addEventListener('DOMContentLoaded', startGame);
