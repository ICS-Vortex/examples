import React from 'react';
import i18next from '../../i18n';

const DogfightsTableComponent = (props) => {
    const options = {
        filterType: 'checkbox',
        filter: false
    };
    const columns = [
        {
            name: i18next.t('label.time'), field: "time", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return dogfight.time;
                }
            },
        },
        {
            name: i18next.t('label.winner'), field: "winnerCallsign", filter: false, options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return <a href={`/pilot/${dogfight.winnerId}?server=${props.server.id}&tour=${props.tour}`}
                              className={`text-${dogfight.winnerSide?.toLowerCase()}`}>
                        {dogfight.winnerCallsign}
                    </a>;
                }
            },
        },
        {
            name: i18next.t('label.plane'), field: "winnerPlane", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    const url = `${process.env.REACT_APP_API_HOST}/images/planes/${dogfight.winnerPlane?.toLowerCase()}.png`;
                    return <div title={dogfight.winnerPlane}>
                        <img width={50} src={url} alt={dogfight.winnerPlane}/>
                    </div>;
                }
            },
        },
        {
            name: i18next.t('label.loser'), field: "loserCallsign", filter: false, options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    return <a href={`/pilot/${dogfight.loserId}?server=${props.server.id}&tour=${props.tour}`}
                              className={`text-${dogfight?.loserSide?.toLowerCase()}`}>
                        {dogfight.loserCallsign}
                    </a>;
                }
            },
        },
        {
            name: i18next.t('label.plane'), field: "loserPlane", options: {
                headerNoWrap: true,
                customBodyRender: (dogfight) => {
                    const url = `${process.env.REACT_APP_API_HOST}/images/planes/${dogfight.loserPlane?.toLowerCase()}.png`;
                    return <div title={dogfight.loserPlane}>
                        <img width={50} src={url} alt={dogfight.loserPlane}/>
                    </div>;
                }
            },
        },
    ];
    return <>
        {/*<MaterialDatatable*/}
        {/*    title={props.server.name + ' - ' + i18next.t('label.dogfights')}*/}
        {/*    data={props.dogfights}*/}

        {/*    columns={columns}*/}
        {/*    options={options}*/}
        {/*/>*/}
    </>;
}

export default DogfightsTableComponent;