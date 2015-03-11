$.ajaxSetup({ cache: false });

var staveHTML, noteHTML;
var staveCount;

$(function() {
	$("#vextabContainer textarea").attr("name", "pieceData");

	//
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-stave.php"
	})
	.done(function(html) {
		staveHTML = html;
	});

	//
	$.ajax({
		type: "GET",
		url: "./view/piece-edit-note.php"
	})
	.done(function(html) {
		noteHTML = html;
	});

	//
	$("#pieceEdit").submit(function() {
		var options = {
			url: "./controller/piece-edit-ajax.php",
			dataType: "json",
			success: function(json) {
				if (json.successMessage)
					$("#pieceEdit .displayMessages").html("<p class='success-message'>" + json.successMessage + "</p>");
				else if (json.errorMessage)
					$("#pieceEdit .displayMessages").html("<p class='error-message'>" + json.errorMessage + "</p>");
			}
		};

		$(this).ajaxSubmit(options);

		return false;
	});

	//
	$("fieldset.stave input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});
	$("fieldset.stave select, fieldset.stave input[type=checkbox]").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});

	//
	staveCount = $("#staveList fieldset.stave").length;
});

function pieceNewStave()
{
	staveCount += 1;

	var html = staveHTML.replace(/_STAVE_NO_/gi, staveCount);

	$("#staveList").append(html);

	$("#pieceStave" + staveCount + " select").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});

	$("#pieceStave" + staveCount + " input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});

	for (var i = 0; i < 4; i++) {
		pieceNewNote(staveCount);
	}

	$($("#pieceStave" + staveCount + " fieldset.noteEntry .duration option[data-vex=':q']")[0]).attr("selected", "selected");
	$($("#pieceStave" + staveCount + " fieldset.noteEntry .octave option[value='4']")[0]).attr("selected", "selected");

	updateStaveNos();

	updateVexTabTextarea();
}

function pieceNewNote(staveNo)
{
	var len = $("#pieceStave" + staveNo + " fieldset.noteEntry").length + 1;

	var html = noteHTML.replace(/_STAVE_NO_/gi, staveNo).replace(/_NOTE_NO_/gi, len);

	$("#pieceStave" + staveNo + " .notes").append(html);

	$("#pieceStaveNote" + len + " select, #pieceStaveNote" + len + " input[type=checkbox]").each(function() {
		$(this).change(function() { updateVexTabTextarea(); });
	});

	$("#pieceStaveNote" + len + " input").each(function() {
		$(this).keyup(function() { updateVexTabTextarea(); });
	});

	updateVexTabTextarea();
}

function pieceDeleteNote(staveNo, noteNo)
{
	$("#pieceStave" + staveNo + " #pieceStaveNote" + noteNo).remove();

	updateVexTabTextarea();
}

function pieceDeleteStave(staveNo)
{
	$("#pieceStave" + staveNo).remove();

	updateStaveNos();

	updateVexTabTextarea();
}

function updateStaveNos()
{
	var i = 1;

	$("#staveList fieldset.stave").each(function() {
		$(this).find(".staveNo").html(i);

		i++;
	});
}

function updateVexTabTextarea()
{
	var s = "";
	var clef, key, topSpace, bottomSpace, topTime, bottomTime;
	var note, duration, octave, dotted;

	var noteField;

	$("fieldset.stave").each(function() {
		clef        = $(this).find(".clef option:selected").data("vex");
		key         = $(this).find(".key option:selected").data("vex");
		topSpace    = $(this).find(".topSpace").val();
		bottomSpace = $(this).find(".bottomSpace").val();
		topTime     = $(this).find(".topTime").val();
		bottomTime  = $(this).find(".bottomTime").val();

		s += "options space=" + topSpace + "\n";
		s += "tabstave tablature=false notation=true\n";
		s += "key=" + key + "\n";
		s += "time=" + topTime + "/" + bottomTime + "\n";
		s += "clef=" + clef + "\n";

		s += "notes ";

		$(this).find(".noteEntry").each(function() {
			noteField = $(this).find(".note option:selected");

			note     = noteField.data("vex");
			duration = $(this).find(".duration option:selected").val().length ? $(this).find(".duration option:selected").data("vex") : duration;
			octave   = $(this).find(".octave option:selected").val().length ? $(this).find(".octave option:selected").val() : octave;
			dotted   = $(this).find(".dotted:checked").length ? "d" : "";

			s += duration + dotted + " " + note + (noteField.data("non-note") == "0" ? "/" + octave : "") + " ";
		});

		s += "\noptions space=" + bottomSpace + "\n";

	});

	$("#vextabContainer textarea").val(s).keyup();
}
