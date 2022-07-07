import React from 'react';
import {Provider} from 'react-redux';
import {Router} from 'react-router';
import {Route, Switch} from "react-router-dom";
import NavbarComponent from "../Navbar/NavbarComponent";
import {AppRoutes} from "../../routes/appRoutes";
import history from "../../history";
import {store} from "../../store";
import {QueryParamProvider} from "use-query-params";
import FooterComponent from "../Footer/FooterComponent";
import {ToastContainer} from 'react-toastify';
import CookieConsent from "react-cookie-consent";

const App = () => {
    return (
        <Provider store={store}>
            <Router history={history}>
                <QueryParamProvider ReactRouterRoute={Route}>
                    <ToastContainer/>
                    <div className="wrapper wrapper_fixed-header">
                        <NavbarComponent/>
                        <Switch>
                            {AppRoutes.map((route) => (
                                <Route key={route.path} exact={route.exact} path={route.path}
                                       component={route.component}/>
                            ))}
                        </Switch>
                        <FooterComponent/>
                        <CookieConsent
                            location="bottom"
                            buttonText="Accept all cookies"
                            cookieName="vpc-cookie-consent"
                            style={{background: "#2B373B"}}
                            buttonStyle={{color: "#4e503b", fontSize: "13px"}}
                            expires={150}
                        >
                            This website uses cookies to enhance the user experience.
                            By continuing to browse or by clicking <b>Accept</b>,
                            you agree to the storing of cookies on your device to enhance your site experience and for
                            analytical purposes.
                        </CookieConsent>
                    </div>
                </QueryParamProvider>
            </Router>
        </Provider>
    );
}

export default App;
