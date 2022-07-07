import {
    FETCH_USER,
    FETCH_USER_ERROR,
    FETCH_USER_SUCCESS,
    GET_USER,
} from "../types/user";

const initialState = {
    user: {},
    loading: false,
    error: null
};


const userReducer = (state = initialState, action) => {
    switch (action.type) {
        case FETCH_USER:
            return {loading: true, error: null, user: {}}
        case GET_USER:
            return {loading: true, error: null, user: {}}
        case FETCH_USER_SUCCESS:
            return {loading: true, error: null, user: action.payload}
        case FETCH_USER_ERROR:
            return {loading: true, error: action.payload, user: {}}
        default:
            return state;
    }
};
export default userReducer;