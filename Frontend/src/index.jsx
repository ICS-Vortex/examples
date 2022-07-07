import React from 'react';
import ReactDOM from 'react-dom';
import App from "./components/App/App";
import {createStore} from 'redux';
import {baseReducer, initialState} from "./reducers/baseReducer";
import {Provider} from 'react-redux';
import {CookiesProvider} from 'react-cookie';
import i18next from './i18n';
import 'bootstrap/scss/bootstrap.scss';
import 'bootstrap/scss/bootstrap-grid.scss';
import 'bootstrap/scss/bootstrap-utilities.scss';
import 'bootstrap/scss/bootstrap-reboot.scss';
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import 'react-toastify/dist/ReactToastify.css';
import 'devextreme/dist/css/dx.common.css'
import 'devextreme/dist/css/dx.darkviolet.css'
import './assets/styles/style.scss';
import './assets/styles/app.scss';

const lang = localStorage.getItem('locale') || 'us';

const store = createStore(
    baseReducer,
    initialState
);
i18next.changeLanguage(lang).then().finally();

ReactDOM.render(
    <React.Fragment>
        <Provider store={store}>
            <CookiesProvider>
                <App/>
            </CookiesProvider>
        </Provider>
    </React.Fragment>,
    document.getElementById('root')
);

