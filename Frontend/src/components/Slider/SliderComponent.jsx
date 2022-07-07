import React, {useEffect} from 'react';
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import i18next from "../../i18n";
import {LANGUAGE_ENGLISH, LANGUAGE_RUSSIAN} from "../../constants/languages";
import Carousel from 'react-bootstrap/Carousel'

const SliderComponent = () => {
    const [slides, setSlides] = React.useState([]);

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/slides/list`)
            .then(response => response.json())
            .then(data => {
                setSlides(data);
            })
            .catch(error => {
                // console.log(error.message);
            })
            .finally(() => {

            });
    }, []);

    const imagesUrl = process.env.REACT_APP_API_HOST + '/uploads/images/slides/';

    return <Carousel controls={false} indicators={false}>
        {slides.map((slide, index) => (
            <Carousel.Item key={index}>
                <img className="d-block w-100" src={encodeURI(imagesUrl + slide.image)} alt={slide.titleEn}/>
                <Carousel.Caption>
                    <h1 className="text-uppercase">
                        {i18next.language === LANGUAGE_ENGLISH && slide.titleEn}
                        {i18next.language === LANGUAGE_RUSSIAN && slide.title}
                    </h1>
                </Carousel.Caption>
            </Carousel.Item>
        ))}
    </Carousel>;
}

export default SliderComponent;
