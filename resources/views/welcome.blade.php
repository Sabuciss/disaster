<h1>MysteryMeal.exe</h1>
<div class="section" id="receptes">
	<form method="POST" action="{{ route('recipes.search') }}" style="margin-bottom: 24px;">
		@csrf
		<label for="ingredients">Ievadi produktus (atdala ar komatu):</label><br>
		<input type="text" id="ingredients" name="ingredients" value="{{ old('ingredients', isset($ingredients) ? $ingredients : '') }}" placeholder="piens, siers, makaroni, vista" style="width: 70%; padding: 8px; margin: 8px 0; border-radius: 4px; border: 1px solid #ccc;">
		<button type="submit" class="btn">Meklēt receptes</button>
	</form>

	@if(isset($error) && $error)
		<div style="color: #c53030; margin-bottom: 16px;">{{ $error }}</div>
	@endif

	@if(isset($recipes) && count($recipes))
		<div style="margin-bottom: 16px;">
			<strong>Atrastas receptes:</strong>
			<ul>
				@foreach($recipes as $recipe)
					<li>
						{{ $recipe['title'] ?? $recipe['name'] ?? 'Recepte' }}
						@if(isset($recipe['image']))
							<br><img src="{{ $recipe['image'] }}" alt="Attēls" style="max-width:120px; border-radius:6px; margin:6px 0;">
						@endif
					</li>
				@endforeach
			</ul>
		</div>
	@endif
</div>

<div class="section" id="minigame">
	<h2>Mini-spēle: Raining food</h2>
	<canvas id="gameCanvas" width="400" height="600" style="background:#e0f7fa;display:block;margin:0 auto;border-radius:8px;"></canvas>
	<div style="text-align:center;margin-top:10px;">
		<button onclick="startGame()" class="btn">Sākt spēli</button>
		<div id="score" style="margin-top:8px;font-weight:bold;"></div>
		<div id="gameOver" style="color:#c53030;font-weight:bold;"></div>
	</div>
</div>
<script>
// Vienkārša minigame implementācija (Raining food)
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const foodItems = ['🍏','🍔','🍕','🍟','🍎','🍇','🍌','🥦','🥕','🍞'];
const junkItems = ['🧻','💊','🪙','🧲','🧦','🪥','🧴','🧢'];
let objects = [], player = {x:200, y:570, w:60, h:20}, score = 0, speed = 2, running = false, gameInterval;

function startGame() {
	objects = [];
	score = 0;
	speed = 2;
	running = true;
	document.getElementById('gameOver').innerText = '';
	document.getElementById('score').innerText = 'Punkti: 0';
	if(gameInterval) clearInterval(gameInterval);
	gameInterval = setInterval(gameLoop, 20);
}

function gameLoop() {
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
		if(o.y+32>player.y && o.x>player.x-20 && o.x<player.x+player.w+10) {
			if(o.type==='food') {
				score++;
				document.getElementById('score').innerText = 'Punkti: '+score;
				objects.splice(i,1); i--;
				speed = 2 + Math.floor(score/10);
			} else {
				running = false;
				document.getElementById('gameOver').innerText = 'Spēle beigusies! Punkti: '+score;
				clearInterval(gameInterval);
				return;
			}
		}
	}
	// Remove off-screen
	objects = objects.filter(o=>o.y<620);
}
// Kontrole ar bultām
document.addEventListener('keydown',function(e){
	if(!running) return;
	if(e.key==='ArrowLeft') player.x=Math.max(0,player.x-30);
	if(e.key==='ArrowRight') player.x=Math.min(340,player.x+30);
});
</script>
   </div>
</body>
</html>
