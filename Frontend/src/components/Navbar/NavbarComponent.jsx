import * as React from 'react';
import {useEffect} from 'react';
import {PAGE_ABOUT_US, PAGE_COUPONS, PAGE_HOME, PAGE_LOGIN, PAGE_PROFILE} from "../../constants/routes";
import history from "../../history";
import LanguageComponent from "../Language/LanguageComponent";
import i18next from "i18next";
import {Link} from 'react-router-dom';
import {Container, Nav, Navbar} from "react-bootstrap";
import {logout, useAuth} from '../../providers/authProvider';
import logo from '../../assets/images/logo.png'
import {useTypedSelector} from "../../hooks/useTypedSelector";
import {useDispatch} from "react-redux";
import {fetchUser} from "../../store/action-creators/user";
import {LANGUAGE_ENGLISH} from "../../constants/languages";
import i18n from "../../i18n";

const NavbarComponent = () => {
    const [tournament, setTournament] = React.useState({});

    const {user} = useTypedSelector(state => state.user);
    const dispatch = useDispatch();
    const [isLogged] = useAuth();
    const handleLogout = () => {
        logout();
        history.push(PAGE_LOGIN);
    };
    const handleProfile = () => {
        history.push(PAGE_PROFILE);
    };
    const handleCouponsLink = () => {
        history.push(PAGE_COUPONS);
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/tournaments/current`)
            .then(r => r.json())
            .then(data => {
                setTournament(data);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, []);

    useEffect(() => {
        if (isLogged) {
            dispatch(fetchUser());
        }
    }, [isLogged]);

    return <Navbar collapseOnSelect expand="lg" bg="dark" variant="dark">
        <Container>
            <Navbar.Brand href="/">
                <img width={150} src={logo} alt="VIRPIL Servers"/>
            </Navbar.Brand>
            <Navbar.Toggle aria-controls="responsive-navbar-nav"/>
            <Navbar.Collapse id="responsive-navbar-nav">
                <Nav className="me-auto">
                    <Nav.Link href={PAGE_HOME}>
                        {i18next.t('page.home')}
                    </Nav.Link>
                    <Nav.Link href={PAGE_ABOUT_US}>{i18next.t('page.about_us')}</Nav.Link>
                    {tournament?.id && <Nav.Link href={`/tournament/${tournament.id}/home`}>
                        <span>{i18n.t('label.tournament')}</span> {i18next.language == LANGUAGE_ENGLISH ? tournament.titleEn : tournament.title}
                    </Nav.Link>}
                </Nav>
                <LanguageComponent/>
                <Nav>
                    {!isLogged && <Link to={PAGE_LOGIN}>
                        {i18next.t('button.signin')}
                    </Link>}
                </Nav>
                {isLogged && user?.id && <Nav title={user?.username} id="collasible-nav-dropdown">
                    <Navbar.Text>
                        Signed in as: <a className="text-danger font-weight-bold" href="#"
                                         onClick={handleProfile}><b>{user?.username}</b></a>
                    </Navbar.Text>
                    <Nav.Link onClick={handleProfile} href="#">{i18next.t('button.account')}</Nav.Link>
                    <Nav.Link onClick={handleCouponsLink} href="#">{i18next.t('button.coupons')}</Nav.Link>
                    <Nav.Link onClick={handleLogout} href="#">{i18next.t('button.signout')}</Nav.Link>
                </Nav>}
            </Navbar.Collapse>
        </Container>
    </Navbar>;
};

export default NavbarComponent;
