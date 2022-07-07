import React from 'react';
import i18next from "i18next";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../constants/languages";
import {Nav} from "react-bootstrap";

const LanguageComponent = () => {
    const handleChange = (locale) => {
        localStorage.setItem('locale', locale);
        window.location.reload();
    };

    return (
        <Nav className="lang__menu">
            <Nav.Link href="#" onClick={() => {
                handleChange(LANGUAGE_ENGLISH)
            }} className={i18next.language === LANGUAGE_ENGLISH ? 'active' : ''}>
                <img src="/images/flag-en.png" alt="VIRPIL Servers EN"/>
            </Nav.Link>
            <Nav.Link href="#" onClick={() => handleChange('ru')}
                      className={i18next.language === LANGUAGE_RUSSIAN ? 'active' : ''}>
                <img src="/images/flag-ru.png" alt="VIRPIL Servers RU"/>
            </Nav.Link>
        </Nav>
    );
}

export default LanguageComponent;