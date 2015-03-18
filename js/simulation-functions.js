// fizzing and whirlpool shared variables
var run = false;             // when false, the simulation will be off
var vexCanvas, vexContext;   // for the vexTab original canvas
var fizzingCanvas, fizzingContext; // for the overlay canvas
var whirlpoolCanvas, whirlpoolContext; // for the overlay canvas
var canvasWidth, canvasHeight;
var canvasCentre = [];       // holds x and y position of the canvas centre

// fizzing variables
var scale;                   // scale value of the VexTab canvas
var x;                       // x coordinate of start of lines
var staveLineHeight;         // height of stave lines in pixels
var staveSpaceHeight;        // height of stave space between lines in pixels
var staveSpaceWidth;         // width of the stave space in pixels
var blocks;                  // array of all Blocks

// whirlpool variables
var squares;   // list of the squares that will move
var squareSize = 10;         // the length of each square

// multiple browser compatible animation frame
var requestAnimationFrame = window.requestAnimationFrame ||
							window.mozRequestAnimationFrame ||
							window.webkitRequestAnimationFrame ||
							window.msRequestAnimationFrame;

// this function is called when the page finishes loading
$(function() {
	// set vexTab canvas element
	vexCanvas = document.getElementsByClassName("vex-canvas")[0];
	vexContext = vexCanvas.getContext('2d');

	// set canvas width and height
	canvasWidth = vexCanvas.width;
	canvasHeight = vexCanvas.height;

	// set the scale value
	scale = parseFloat($("#vextabContainer").attr("scale"));

	// set the x position
	x = 12*scale;

	// set the stave space width
	staveSpaceWidth = canvasWidth - x*2;

	// create the overlay canvas
	$("#vextabContainer").append("<canvas id='fizzingCanvas' width='" + canvasWidth + "' height='" + canvasHeight + "'></canvas>");

	// set overlay canvas element
	fizzingCanvas = document.getElementById("fizzingCanvas");
	fizzingContext = fizzingCanvas.getContext('2d');

	// set the canvas centre
	canvasCentre[0] = Math.floor(canvasWidth/2);
	canvasCentre[1] = Math.floor(canvasHeight/2);

	// create the overlay canvas
	$("#vextabContainer").append("<canvas id='whirlpoolCanvas' width='" + canvasWidth + "' height='" + canvasHeight + "'></canvas>");

	// set overlay canvas element
	whirlpoolCanvas = document.getElementById("whirlpoolCanvas");
	whirlpoolContext = whirlpoolCanvas.getContext('2d');
});

/**
 * This method assumes the dimensions of the canvas layout through personal inspection.
 * This method was by far the fastest and was chosen for implementation.
 * Values:
 *    - height of the lines in the stave = 2px
 *    - height of the space between the lines on the stave = 8px
 *    - space at the top of the canvas = 38px
 *    - space from the top of 1 stave to the top of the next = 90px
 *    - manual spacings entered are in pixels and therefore easy to account for when
 *      calculating positions of stave lines.
 */
function startFizzing()
{
	$(".simButton").attr("disabled", "disabled");

	run = true;
	blocks = new Array();

	var staveNo = $(".editor").val().match(/tabstave/ig).length; // amount of staves in the canvas

	staveLineHeight = 2*scale, staveSpaceHeight = 8*scale;

	var spacings = $(".editor").val().match(/space=([0-9-]+)/ig); // the individual spacings in the canvas
	// remove the text and just leave the integer value
	for (var i = 0; i < spacings.length; i++) {
		spacings[i] = parseFloat(spacings[i].split("=")[1]);
	};

	// defaults
	var topSpace = (38+1)*scale; // +1 because 39 is where the first line begins
	var topToTopSpace = 90*scale;

	// each new stave has to account for previous spacings
	// so tally up the total space as the loop progresses
	var cumulativeSpace = 0;

	var y;

	// loop over each stave to find the stave line positions
	for (var i = 0; i < staveNo; i++) {
		// stave i has to account for space i*2 and i*2-1
		// when i = 0, then i*2-1 is out of bounds, the if below accounts for it.
		// a full explanation of this spacing calculation will be in the report
		if (i == 0) {
			cumulativeSpace += (spacings[i*2])*scale;
		}
		else {
			cumulativeSpace += (spacings[i*2] + spacings[i*2-1])*scale;
		}

		// 5 lines per stave
		for (var j = 0; j < 4; j++) {
			y = (topSpace) + (topToTopSpace*i) + ((staveLineHeight+staveSpaceHeight)*j) + cumulativeSpace;

			blocks.push(new Block(y + staveLineHeight));
		};
	};

	drawFizzing();
}

/**
 * Class Block.
 * Holds the relevant block information.
 * A block is the space between stave lines.
 *
 * The final version creates new random pixel arrays for each block
 * over each recursion to display a new block of pixels on each recursion.
 *
 * The replication version creates a random pixel array when the
 * block is created, then flickers it on and off at random intervals.
 */
function Block(y)
{
	this.y = y;
	this.counter = 0;
}

Block.prototype.update = function()
{
	var pixels = new Array();
	var r;

	for (var i = 0; i < staveSpaceWidth; i++) {
		for (var j = 2; j < staveSpaceHeight-2; j++) {
			pixels.push(100);
			pixels.push(100);
			pixels.push(100);

			r = Math.random() * 2;

			pixels.push(r < 1 ? 255 : 0);
		};
	};

	var imageData = fizzingContext.createImageData(staveSpaceWidth, staveSpaceHeight-4);
	imageData.data.set(pixels);

	// draw the square on the canvas
	fizzingContext.putImageData(imageData, x, this.y+2);
}

function drawFizzing()
{
	// clear the canvas
	fizzingContext.clearRect(0, 0, canvasWidth, canvasHeight);

	// if simulation has not be stopped
	if (run) {
		// loop over each square
		for (var i = 0; i < blocks.length; i++) {
			var block = blocks[i];
			block.update();
		}

		// set recursion loop
		requestAnimationFrame(drawFizzing);
	}
}



/**
 * This function creates the Square objects based on the size of each square.
 * Each Square is added to the squares array.
 * Calls drawWhirlpool() to initialise the simulation.
 */
function startWhirlpool()
{
	$(".simButton").attr("disabled", "disabled");

	run = true; // lets simulation know to run

	squares = new Array(); // ensure the array is emptied before running (again)

	var data; // holds data from the getImageData() method

	// loop over the y axis
	for (var y = 0; y < canvasHeight; y+=squareSize) {
		// loop over the x axis
		for (var x = 0; x < canvasWidth; x+=squareSize) {
			data = vexContext.getImageData(x, y, squareSize, squareSize);

			// add Square to the squares array
			squares.push(new Square(x, y, data));
	    }
	}

	// start simulation
	drawWhirlpool();
}

/**
 * Class Square.
 * Holds the square's x and y coordinates. Holds the pixel data for the square.
 * Calculates the angle of the square to the canvas centre position.
 */
function Square(x, y, data)
{
	this.x = x;
	this.y = y;
	this.data = data;

	var deltaY = (y + squareSize / 2) - canvasCentre[1];
	var deltaX = (x + squareSize / 2) - canvasCentre[0];

	this.angle = Math.atan2(deltaY, deltaX); // calculate the angle to the centre position using inverse tangent
	this.angle = this.angle * 180 / Math.PI; // convert the angle form radians to degrees

	this.distance = 0;
}

/**
 * Calculates the new position of a square based on its angle.
 */
Square.prototype.update = function ()
{
	// set how far to move each square from its original position
	this.distance += 10;

	var newX = this.x, newY = this.y;

	// based on angle from the centre, calculate new positions
	// see Design section of report for full explanation
	if (this.angle == 0) { // right
		newX += this.distance;
	}
	else if (this.angle > 0 && this.angle < 90) {
		newY += this.distance * (this.angle / 90);
		newX += this.distance * (1 - (this.angle / 90));
	}
	else if (this.angle == 90) { // down
		newY += this.distance;
	}
	else if (this.angle > 90 && this.angle < 180) {
		newY += this.distance * (1 - ((this.angle-90) / 90));
		newX -= this.distance * ((this.angle-90) / 90);
	}
	else if (this.angle == 180) { // left
		newX -= this.distance;
	}
	else if (this.angle < -90) { //
		newY -= this.distance * (1 - ((this.angle+90) / -90));
		newX -= this.distance * ((this.angle+90) / -90);
	}
	else if (this.angle == -90) { // up
		newY -= this.distance;
	}
	else if (this.angle > -90 && this.angle < 0) {
		newY -= this.distance * (this.angle / -90);
		newX += this.distance * (1 - (this.angle / -90));
	}

	// draw the square on the canvas
	whirlpoolContext.putImageData(this.data, newX, newY);

	if (this.distance > 10) {
		// reset distance to 0
		this.distance = 0;
	}
};

/**
 * Clears the canvas then loops over the squares array to update them.
 * The update call draws the new square on the canvas.
 */
function drawWhirlpool() {
	// clear the canvas
	whirlpoolContext.clearRect(0, 0, canvasWidth, canvasHeight);

	// if simulation has not be stopped
	if (run) {
		// loop over each square
		for (var i = 0; i < squares.length; i++) {
			var mySquare = squares[i];
			mySquare.update();
		}

		// set recursion loop
		requestAnimationFrame(drawWhirlpool);
	}
}


/**
 * Stops all simulations from running.
 * Useful to stop fizzing when whirlpool start, and vice versa.
 * Also useful to stop whirlpool running twice, same for fizzing.
 */
function stopAll()
{
	run = false;

	$(".simButton").removeAttr("disabled");
}
