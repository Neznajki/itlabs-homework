$(() => {
    let challengeCreator = new ChallengeCreator();
    $('#submitTeamCreate').on('click', function () {
        try {
            challengeCreator.validate();
            challengeCreator.submitForm();
        } catch (e) {
            alert(e.message)
        }
    });
});

let ChallengeCreator = function () {
    this.$playOf = $('[name="playOfType"]');
    this.$teams = $('[name="teams"]');
    this.$submit = $('#submitTeamCreate');

    this.validate = () => {
        let playOfGames = getSelectedPlayOf().data('games');
        let requiredTeams = playOfGames * 4;
        let selectedTeams = getSelectedTeam();

        if (selectedTeams.length < requiredTeams) {
            throw new Error('not enough teams for tournament required ' + requiredTeams + ' teams');
        }
    };

    this.submitForm = () => {
        $.ajax(getSubmitUrl(), {
            'data': {
                'teams': getTeams()
            },
            'method': 'POST',
            'accepts': 'application/json',
            'success': (response) => {
                window.location.href = this.$submit.data('redirect').replace(encodeURIComponent('$challengeId'), response.data.id);
            },
            'error': (response) => {
                if (response.responseJSON && response.responseJSON.message) {
                    alert(response.responseJSON.message);
                } else {
                    alert('something gone wrong');
                }
            }
        });
    };


    let getSelectedPlayOf = () => {
        return this.$playOf.filter(':checked');
    };

    let getSelectedTeam = () => {
        return this.$teams.filter(':checked');
    };

    let getSubmitUrl = () => {
        return this.$submit.data('action').replace(encodeURIComponent('$playOfId'), getSelectedPlayOf().val());
    };

    let getTeams = () => {
        let teams = [];

        $.each(this.$teams.filter(':checked'), function () {
            teams.push($(this).val());
        });

        return teams;
    };

    return this;
};