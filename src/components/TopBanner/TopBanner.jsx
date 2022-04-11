import React, { Component, Fragment } from 'react'
import { Container,Row,Col } from 'react-bootstrap'
import '../../assets/css/bootstrap.css'

import '../../assets/css/custom.css'

export class TopBanner extends Component {
  render() {
    return (
      <Fragment>
          <Container fluid={true} className='topfixedBanner p-0'>
            <div className="topBannerOverlay">
              <Container className='topContent'>
                <Row>
                  <Col className='text-center'>
                  
                <h1>مديرية التربية والتعليم رفح</h1>
                <h2>ابداع وتميز</h2>
                  </Col>






                </Row>






              </Container>





            </div>

          </Container>
      </Fragment>
    )
  }
}

export default TopBanner