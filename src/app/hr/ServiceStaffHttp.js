define([
  'app',
], function (app) {
  app.registerFactory('StaffHttp', [
    '$http',
    'transformRequestAsFormPost',
  function ($http, transformRequestAsFormPost) {
    return {
      save: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/saveStaff',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      getEditInfo: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/getEditInfo',
          params: paramObj
        });
      },
      getProvinceCity: function () {
        return $http({
          method: 'get',
          url: '/bms/staff/getProvinceCityList'
        });
      },
      getDeptList: function () {
        return $http({
          method: 'get',
          url: '/bms/orgStructure/get3LevelList'
        });
      },
      getGradeList: function () {
        return $http({
          method: 'get',
          url: '/bms/positionSystem/getGrades'
        });
      },
      getGradePosition: function () {
        return $http({
          method: 'get',
          url: '/bms/positionSystem/getGradePositionList'
        });
      },
      getPositionList: function () {
        return $http({
          method: 'get',
          url: '/bms/positionSystem/getPositionList'
        });
      },
      getBasicInfo: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/queryBasicInfo',
          params: paramObj
        });
      },
      getTransferInfo: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/queryTansferInfo',
          params: paramObj
        });
      },
      getMyApproval: function () {
        return $http({
          method: 'get',
          url: '/bms/staff/getMyApproval'
        });
      },
      transferApply: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/applyTransfer',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      transferApprove: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/submitApproval',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      resignSubmit: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/submitResign',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      queryStaffInfo: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/queryStaffInfo',
          // transformRequest: transformRequestAsFormPost,
          // data: paramObj
          params: paramObj
        });
      },
      queryStaffList: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/queryStaffList',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      exportStaffList: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/exportStaffList',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      getApprovalRecord: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/getApprovalRecord',
          params: paramObj
        });
      },
      queryAnalysisIn: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/queryAnalysisIn',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      queryAnalysisOut: function (paramObj) {
        return $http({
          method: 'post',
          url: '/bms/staff/queryAnalysisOut',
          transformRequest: transformRequestAsFormPost,
          data: paramObj
        });
      },
      getManagerList: function (paramObj) {
        return $http({
          method: 'get',
          url: '/bms/staff/getManagerList'
        });
      }
    };
  }]);
});
