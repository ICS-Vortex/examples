import React from 'react';

const MapMarkerComponent = (props) => {
    const capitalize = (name) => {
        name = name.toLowerCase();
        return name.charAt(0).toUpperCase() + name.slice(1);
    };

    const clean = (name) => {
        return name.trim().replace('/', '').replace(' ', '').toLowerCase()
    };
    let type;
    const getImageUrl = () => {
        switch (props.unit.type.toLowerCase()) {

            case '3':
                type = 'Infantry';
                break;
            case 'artillery':
                type = `Armor`;
                break;
            case '13':
                type = 'FARP';
                break;
            case 'sam':
                type = 'SAM'
                break;
            case 'ship':
                type = 'Ship';
                break;
            case 'plane':
                switch (props.unit.side.toLowerCase()) {
                    case 'red':
                        return `${process.env.REACT_APP_API_HOST}/images/planes/${clean(props.unit.title)}_r.png`;
                    case 'blue':
                        return `${process.env.REACT_APP_API_HOST}/images/planes/${clean(props.unit.title)}_b.png`;
                    default:
                        type = 'Air';
                }
                break;
            default:
                type = '';
        }

        return `/images/icons/${capitalize(props.unit.side)}-${type}.png`;
    };

    // const icon = L.divIcon({
    //     iconSize: [1, 1],
    //     iconAnchor: [1, 1],
    //     className: '',
    //     html: `<img width="32px" style="transform: rotate(${props.unit.heading}deg) !important;" alt="${props.unit.title}" src="${getImageUrl()}" />`,
    // });

    return <span>
        {/*<Marker icon={icon} opacity={1}*/}
        {/*        position={{lat: props.unit.latitude, lng: props.unit.longitude}}*/}
        {/*        key={props.unit.id}>*/}
        {/*    <Tooltip>*/}
        {/*      <span>{props.unit.title + ' | ' + props.unit.type}</span>*/}

        {/*    </Tooltip>*/}
        {/*</Marker>*/}
    </span>;
}

export default MapMarkerComponent;