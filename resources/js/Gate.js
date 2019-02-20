// how to do ACL in front end.
//previously backend e gate use korar maddhome admin kina check kortecilam(authServiceProvider.php file e)
//but ota kaj korleo front end e url e localhost/dashboard e gele dashboard component load hoccilo logged in person user howa sotteo.
//etar karon front end e vue component render korci. tai now front er jonno Gate design korbo

export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdminFrontEnd() {
        return this.user.type === "admin";
    }
    isAuthorFrontEnd() {
        return this.user.type === "author";
    }
    isUserFrontEnd() {
        return this.user.type === "user";
    }
    isAdminOrIsAuthorFrontEnd() {
        if (this.user.type === "admin" || this.user.type === "author") {
            return true;
        }
    }
    isAuthorOrIsUserFrontEnd() {
        if (this.user.type === "author" || this.user.type === "user") {
            return true;
        }
    }
}

//now ei Gate class ke app.js e import korle globally jekono component e front end ei ACL control korte parbo ekhon
