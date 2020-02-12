var tictactoe = {
    makeRequest: function (state) {
        $.ajax({
            url: "api/action",
            method: "POST",
            data: JSON.stringify({'state' : state}),
            processData: false,
            contentType: 'application/json'
        }).done(function (data) {
            if (data.winner) {
                tictactoe.somebodyWin(data);
                return;
            }
            if (data.standoff) {
                tictactoe.standoffAlert();
            }
            tictactoe.botAction(data.botAction[0], data.botAction[1], data.botAction[2]);
        })
    },
    somebodyWin: function(data) {
        if (tictactoe.isPlayerWin(data.winner.unit)) {
            tictactoe.winnerAlert(data.winner.actions);
        } else {
            tictactoe.looserAlert(data.winner.actions, data.botAction);
        }
    },
    isPlayerWin: function(unit) {
        return unit == "X";
    },
    winnerAlert: function(actions) {
        tictactoe.winnerAction(actions, 'bg-success');
        tictactoe.finish("alert-success", "You win");
    },
    looserAlert: function(actions, botAction) {
        tictactoe.winnerAction(actions, 'bg-danger');
        tictactoe.finish("alert-danger", "You lose");
        tictactoe.botAction(botAction[0], botAction[1], botAction[2]);
    },
    standoffAlert: function() {
        tictactoe.finish("alert-info", "Standoff");
    },
    finish: function(alertType, message) {
        $("#winner").addClass(alertType).html(message);
        $("table").data("finished", true);
    },
    botAction: function(x, y, unit) {
        var tableElement = $("#tictactoe-table td").filter('[data-x=' + x + ']').filter('[data-y=' + y + ']');
        tableElement.data("unit", unit);
        tableElement.html("<i class='fa fa-circle-o fa-5x'></i>");
    },
    winnerAction: function(winnerActions, bgClass) {
        $.each(winnerActions, function(key, val) {
            $("#tictactoe-table td").filter('[data-x=' + val[0] + ']').filter('[data-y=' + val[1] + ']').addClass(bgClass);
        });
    },
    restart: function () {
        $("#tictactoe-table td").each(function () {
            tictactoe.clearUnit($(this));
            $("#winner").removeClass (function (index, className) {
                return (className.match (/(^|\s)alert-\S+/g) || []).join(' ');
            }).html("");
        });
        $("#tictactoe-table table").data("finished", false);
    },
    clearUnit: function (unit) {
        unit.hasClass("bg-danger") && unit.removeClass("bg-danger");
        unit.hasClass("bg-success") && unit.removeClass("bg-success");
        unit.data("unit", "");
        unit.html("");
    },
    process: function (unit) {
        if (unit.data("unit") !== "" || $("#tictactoe-table table").data("finished")) {
            return false;
        }
        unit.html("<i class='fa fa-times fa-5x'></i>");
        unit.data("unit", "X");
        var state = [];
        $("#tictactoe-table tr").each(function () {
            var td = [];
            $(this).find("td").each(function () {
                td.push($(this).data("unit"));
            });
            state.push(td);
        });

        tictactoe.makeRequest(state);
    }
};

$('.restart').on("click", function () {
    tictactoe.restart();
});

$("#tictactoe-table td").on("click", function () {
    tictactoe.process($(this));
});