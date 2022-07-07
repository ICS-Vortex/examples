import {FETCH_USER, FETCH_USER_ERROR, FETCH_USER_SUCCESS} from "../types/user";
import i18n from "../../i18n";
import {authFetch, logout} from "../../providers/authProvider";

export const fetchUser = () => {
    return async (dispatch) => {
        try {
            dispatch({type: FETCH_USER});
            const request = await authFetch(process.env.REACT_APP_API_HOST + `/api/${i18n.language}/profile/`);
            const response = await request.json();
            if (response.message) {
                logout();
                dispatch({
                    type: FETCH_USER_ERROR,
                    payload: i18n.t('message.fetch_user_error')
                })
            } else {
                dispatch({type: FETCH_USER_SUCCESS, payload: response.user});
            }
        } catch (e) {
            logout();
            dispatch({
                type: FETCH_USER_ERROR,
                payload: i18n.t('message.fetch_user_error')
            })
        }
    };
};
