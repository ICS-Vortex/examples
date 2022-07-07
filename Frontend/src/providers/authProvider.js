import {createAuthProvider} from 'react-token-auth';
import history from "../history";
import {PAGE_LOGIN} from "../constants/routes";

export const {useAuth, authFetch, login, logout, getSession, getSessionState} = createAuthProvider({
    storageKey: 'token',
    getAccessToken: session => session.token,
    storage: localStorage,
    onUpdateToken: (token) => {
        return fetch(process.env.REACT_APP_API_HOST + '/api/token/refresh', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({refreshToken: token.refreshToken})
        })
            .then(r => r.json())
            .catch((err) => {
                logout();
                console.error('Token Refresh error:', err);
                history.push(PAGE_LOGIN);
            })
    }
});
