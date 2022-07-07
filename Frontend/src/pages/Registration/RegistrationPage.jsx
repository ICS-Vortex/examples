import React, {useEffect} from "react";
import {Button, Col, Container, Form, Row, Spinner} from "react-bootstrap";
import i18next from "../../i18n";
import i18n from "../../i18n";
import axios, {AxiosResponse} from "axios";
import DropDownBox from 'devextreme-react/drop-down-box';
import DataGrid, {Column, Selection} from "devextreme-react/data-grid";
import CustomStore from 'devextreme/data/custom_store';

const RegistrationPage = () => {
    const [ucid, setUcid] = React.useState('');
    const [gridBoxValue, setGridBoxValue] = React.useState([]);
    const [validated, setValidated] = React.useState(false);
    const [loading, setLoading] = React.useState(false);
    const [devices, setDevices] = React.useState(null);
    const handleUcidChange: (e: React.ChangeEvent<HTMLInputElement>) => void = (e: React.ChangeEvent<HTMLInputElement>) => {
        setUcid(e.target.value);
    }

    const makeAsyncDataSource = () => {
        setDevices(new CustomStore({
            loadMode: 'raw',
            key: 'id',
            load: () => {
                return fetch(process.env.REACT_APP_API_HOST + '/api/open/game-devices/list')
                    .then(response => response.json());
            }
        }));
    }

    const dataGrid_onSelectionChanged = (e) => {
        setGridBoxValue(e.selectedRowKeys);
    }

    const validateUuid: () => void = () => {
        const url = process.env.REACT_APP_API_HOST + '/api/login/' + i18n.language + '/validate';
        const data = {
            ucid: ucid
        };
        const headers = {
            'Content-Type': 'application/json',
            'X-DCS-UCID': ucid
        };
        setLoading(true);
        axios.post(url, data, {headers: headers})
            .then((response: AxiosResponse) => {
                setValidated(true);
            })
            .catch(err => {

            })
            .finally(() => {
                setLoading(false);
            })
        ;
    }

    const syncDataGridSelection = (e) => {
        setGridBoxValue(e.value || []);
    }

    const formatImage = (cell) => {
        return <>
            <img className="img-thumbnail"
                 src={`${process.env.REACT_APP_API_HOST}/uploads/images/devices/${cell.data.image}`} alt=""/>
        </>
    };

    const formatTitle = (cell) => {
        return <>
            <span className="vertical-center">{cell.data.name}</span>
        </>
    };

    const dataGridRender = () => {
        return (
            <DataGrid
                className="w-100"
                dataSource={devices}
                hoverStateEnabled={true}
                selectedRowKeys={gridBoxValue}
                onSelectionChanged={dataGrid_onSelectionChanged}>
                <Column
                    cssClass={'vertical-center'}
                    dataField="name"
                    cellRender={formatTitle}
                    caption="Title"
                    dataType="string"
                    alignment="right"
                />
                <Column
                    dataField="image"
                    caption="Image"
                    cellRender={formatImage}
                />
                <Selection mode="multiple"/>
            </DataGrid>
        );
    }

    useEffect(() => {
        makeAsyncDataSource();
    }, []);

    return <React.Fragment>
        <main className="main main_gradient text-white">
            <div className="content">
                <div className="main__content">
                    <Container>
                        <Row>
                            <Col>
                                <h2>Registration page</h2>
                                {!validated && <div>
                                    <Form>
                                        <Form.Group className="mt-3" controlId="formBasicPassword">
                                            <Form.Label>{i18next.t('label.ucid')}</Form.Label>
                                            <Form.Control type="password" placeholder="Pilot UCID"
                                                          required onChange={handleUcidChange}
                                                          value={ucid}
                                                          defaultValue={'857c88c002a77dbc057cb4faa027706c'}/>
                                            <div className="d-grid gap-2 mt-4">
                                                <Button size="lg" variant="outline-warning" type="button"
                                                        onClick={validateUuid}>
                                                    {loading && <Spinner
                                                        as="span"
                                                        animation="border"
                                                        role="status"
                                                        aria-hidden="true"
                                                        className="mr-3"
                                                    />}
                                                    {i18next.t('button.validate')}
                                                    {loading &&
                                                    <Spinner className="ml-4" animation="border" variant="danger"/>}

                                                </Button>
                                            </div>
                                        </Form.Group>
                                    </Form>
                                </div>}
                                {validated && <>
                                    <div className="form">
                                        <div className="dx-fieldset">
                                            <div className="dx-field">
                                                <div className="dx-field-label">Devices you like to play with</div>
                                                <div className="dx-field-value">
                                                    <DropDownBox
                                                        value={gridBoxValue}
                                                        valueExpr="id"
                                                        deferRendering={true}
                                                        displayExpr="name"
                                                        placeholder="Select a devices..."
                                                        showClearButton={false}
                                                        dataSource={devices}
                                                        onValueChanged={syncDataGridSelection}
                                                        contentRender={dataGridRender}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </>}
                            </Col>
                        </Row>
                    </Container>
                </div>
            </div>
        </main>
    </React.Fragment>
};

export default RegistrationPage;