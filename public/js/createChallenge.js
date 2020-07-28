$(() => {
    let challengeCreator = new ChallengeCreator();
    $('#submitTeamCreate').on('click', function () {
        try {
            challengeCreator.validate();
        } catch (e) {
            alert(e.message)
        }
    });
});

let ChallengeCreator = function () {
    this.$playOf = $('[name="playOfType"]');
    this.$teams = $('[name="teams"]')

    this.validate = () => {
        let playOfGames = getSelectedPlayOf().data('games');
        let requiredTeams = playOfGames * 4;
        let selectedTeams = getSelectedTeam();

        if (selectedTeams.length < requiredTeams) {
            throw new Error('not enough teams for tournament required ' + requiredTeams +' teams');
        }
    };

    let getSelectedPlayOf = () => {
        return this.$playOf.filter(':checked');
    }

    let getSelectedTeam = () => {
        return this.$teams.filter(':checked');
    }

    return this;
};