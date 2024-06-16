

export enum RoleNames {
  User = 'ROLE_USER',
  Admin = 'ROLE_ADMIN',
  Moderator = 'ROLE_MODERATOR',
}

declare global {
  interface IRole {
    _id?: number;
    name: RoleNames;
  }

  interface IUser {
    id?: number;
    name: string;
    password?: string;
    email: string;
    roles?: IRole[];
    roleNames?: RoleNames[];
    accessToken?: string;
  }

  interface ITranscription {
    id?: number;
    name: string;
    description?: string;
    file: File;
    body?: string;
    storage_info?: object;
    createdAt?: string;
    updatedAt?: string;
  }

  interface IAUthState {
    status: {
      loggedIn: boolean;
    };
    user: IUser | null;
  }

}

export default {};