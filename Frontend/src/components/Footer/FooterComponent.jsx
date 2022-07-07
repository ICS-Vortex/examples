import React, {useEffect} from 'react';
import i18next from "i18next";
import {PAGE_ABOUT_US} from "../../constants/routes";


const FooterComponent = () => {
    const [icons, setIcons] = React.useState([]);
    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/icons/list`)
            .then(r => r.json())
            .then(data => {
                setIcons(data);
            })
    }, []);

    return (
        <footer className="footer text-white">
            <div className="content footer__content">
                <div className="footer__col">
                    <div className="footer__title">{i18next.t('label.info')}</div>
                    <div className="footer__text">
                        <p><a className="text-primary text-decoration-none"
                              href={PAGE_ABOUT_US}>{i18next.t('page.about_us')}</a></p>
                        <p>
                            <a href="https://virpil.by/">{i18next.t('message.virpil_by_shop')}</a>
                        </p>
                        <p><a href="https://virpil-controls.eu">{i18next.t('message.virpil_com_shop')}</a></p>
                        <p><a href="https://support.virpil.com" rel="noreferrer"
                              target="_blank">{i18next.t('label.technical_support')}</a></p>
                    </div>
                </div>
                <div className="footer__col">
                    <div className="footer__title">{i18next.t('label.mail')}</div>
                    <div className="footer__mailto">
                        <a className="text-decoration-none" href="mailto:support@virpil.com">support@virpil.com</a>
                    </div>
                    <a href="#" data-fancybox="" className="quality-control text-decoration-none">
                        <span className="quality-control__title">{i18next.t('label.quality_control_service')}</span>
                        <span
                            className="quality-control__desc">{i18next.t('message.we_wait_wishes')}</span>
                    </a>
                </div>
                <div className="footer__col footer__col_last">
                    <div className="messenger-title"/>
                    <ul className="social">
                        <li key={0}>{i18next.t('label.social_networks')}</li>
                        {icons.map((icon, i) => (
                            <li key={i + 1}>
                                <a href={icon.url} className={`social__${icon.icon}`}
                                   target={icon.newWindow ? '_blank' : ''}>
                                    <i className={`fab fa-${icon.icon} text-primary m-2`}/>
                                </a>
                            </li>
                        ))}
                    </ul>
                </div>
            </div>
        </footer>
    );
}

export default FooterComponent;