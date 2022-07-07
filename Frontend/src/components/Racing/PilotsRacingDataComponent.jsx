import React, {useEffect} from 'react';
import i18n from "../../i18n";
import moment from "moment";
import DataGrid, {Column, Pager, Paging, SearchPanel} from "devextreme-react/data-grid";

const PilotsRacingDataComponent = ({tournament}) => {
    const pageSizes = [25, 50, 100];
    const dataGridAttributes = {
        id: 'racing-data',
        class: 'text-white'
    };
    const [racingData, setRacingData] = React.useState([]);

    const formatTime = (cell) => {
        return moment.utc(cell.value * 1000).format('mm:ss.SS');
    };
    const formatPilot = (cell) => {
        return <>
            <span title={cell.data.country?.toUpperCase()} className={`m-2 flag-icon flag-icon-${cell.data.country}`}/>
            <span className={`ml-2`}>{cell.data.callsign}</span>
        </>
    };

    useEffect(() => {
        fetch(process.env.REACT_APP_API_HOST + `/api/open/racing/pilots-data?tournament=${tournament?.id}`)
            .then(r => r.json())
            .then(response => {
                setRacingData(response);
            })
            .catch(e => {
                // console.log(e.message);
            })
        ;
    }, [tournament]);

    return <React.Fragment>
        <div className="stats__block">
            <div className="stats__title-table">{i18n.t('label.general_results')}</div>
            <DataGrid key={1} allowColumnReordering={true} elementAttr={dataGridAttributes} dataSource={racingData}>
                <SearchPanel visible={true} highlightCaseSensitive={true} placeholder={i18n.t('label.search')}/>
                <Column width={40} dataField="id" dataType="number" caption={'#'}/>
                <Column dataField="callsign" dataType="string" caption={i18n.t('label.callsign')}
                        cellRender={formatPilot}/>
                <Column dataField="plane" dataType="string" caption={i18n.t('label.aircraft')}/>
                <Column dataField="time" dataType="string" caption={i18n.t('label.time')} customizeText={formatTime}/>
                <Pager allowedPageSizes={pageSizes} showPageSizeSelector={true}/>
                <Paging defaultPageSize={pageSizes[0]}/>
            </DataGrid>
        </div>

    </React.Fragment>;
};

export default PilotsRacingDataComponent;
